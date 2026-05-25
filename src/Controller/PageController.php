<?php

namespace App\Controller;

use App\Cart\CartHandler;
use App\Cart\CartInterface;
use App\Entity\Product;
use App\Form\AddToCartType;
use App\Model\CartItem;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PageController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ProductRepository $productRepository,
        private readonly CartHandler $cartHandler,
        #[Autowire(service: 'App\Cart\CartInterface')]
        private readonly CartInterface $cartStrategy,
    ) {
    }

    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('page/index.html.twig', [
            'products' => $this->productRepository->findBy([], ['name' => 'ASC']),
        ]);
    }

    #[Route('/categories', name: 'app_categories')]
    public function categories(): Response
    {
        return $this->render('page/browse_categories.html.twig', [
            'categories' => $this->categoryRepository->findAllOrderedByName(),
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_USER')]
    public function profile(): Response
    {
        return $this->render('page/profile.html.twig');
    }

    #[Route('/product/{id}', name: 'app_product_details', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function productDetails(Request $request, int $id): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product instanceof Product) {
            throw new NotFoundHttpException('Product not found.');
        }

        $form = $this->createForm(AddToCartType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$product->isInStock()) {
                $this->addFlash('error', 'This product is out of stock.');

                return $this->redirectToRoute('app_product_details', ['id' => $id]);
            }

            $quantity = (int) $form->get('quantity')->getData();
            $cartItem = CartItem::fromProduct($product, $quantity);
            $this->cartHandler->addItem($cartItem, $this->cartStrategy);

            $this->addFlash('success', sprintf('"%s" added to your cart.', $product->getName()));

            return $this->redirectToRoute('app_cart');
        }

        return $this->render('page/product_details.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/cart', name: 'app_cart')]
    public function cart(): Response
    {
        $cart = $this->cartHandler->getCart($this->cartStrategy);
        $subtotal = $cart->total();
        $shipping = $cart->isEmpty() ? 0.0 : 10.0;
        $tax = round($subtotal * 0.0825, 2);
        $total = $subtotal + $shipping + $tax;

        return $this->render('page/cart.html.twig', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total,
        ]);
    }

    #[Route('/cart/remove/{productId}', name: 'app_cart_remove', requirements: ['productId' => '\d+'], methods: ['POST'])]
    public function removeFromCart(Request $request, int $productId): Response
    {
        if (!$this->isCsrfTokenValid('submit', (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token.');
        }

        $this->cartHandler->removeItem($productId, $this->cartStrategy);
        $this->addFlash('success', 'Item removed from cart.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/category/{slug}', name: 'app_products_by_category')]
    public function productsByCategory(string $slug): Response
    {
        $category = $this->categoryRepository->findOneBySlug($slug);

        if (null === $category) {
            throw new NotFoundHttpException('Category not found.');
        }

        return $this->render('page/products_by_category.html.twig', [
            'category' => $category,
            'products' => $this->productRepository->findByCategory($category),
        ]);
    }
}
