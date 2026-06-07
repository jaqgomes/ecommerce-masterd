<?php
class AppConfigConst
{
    public const string DB_HOST = 'localhost';
    public const string DB_NAME = 'ecommerce';
    public const string DB_USER = 'root';
    public const string DB_PASS = '';

    public const string PATH_INDEX = '/ecommerce-masterd/index.php';
    public const string PATH_ABOUT = '/ecommerce-masterd/about.php';

    public const string PATH_PRODUCTS_DETAILS = '/product-details.html';
    public const string PATH_PRODUCTS_LIST = '/ecommerce-masterd/product/list-product.php';
    public const string PATH_PRODUCTS_UPLOADS = '/ecommerce-masterd/product/uploads/';
    public const string PATH_PRODUCTS_MANAGER = '/ecommerce-masterd/product/list-product-manager.php';
    public const string PATH_PRODUCTS_VIEW = '/ecommerce-masterd/product/view-product.php';
    public const string PATH_PRODUCTS_CREATE = '/ecommerce-masterd/product/create-product.php';
    public const string PATH_PRODUCTS_EDIT = '/ecommerce-masterd/product/edit-product.php';
    public const string PATH_PRODUCTS_DELETE = '/ecommerce-masterd/product/delete-product.php';

    public const string PATH_CART = '/ecommerce-masterd/cart/cart.php';
    public const string PATH_ADD_TO_CART = '/ecommerce-masterd/cart/add-to-cart.php';

    public const string PATH_ORDER_LIST = '/ecommerce-masterd/order/list-order.php';
    public const string PATH_ORDER_CHECKOUT = '/ecommerce-masterd/order/checkout.php';

    public const string PATH_LOGIN = '/ecommerce-masterd/security/login.php';
    public const string PATH_LOGOUT = '/ecommerce-masterd/security/logout.php';
    public const string PATH_REGISTER = '/ecommerce-masterd/security/register.php';
    public const string PATH_PROFILE = '/ecommerce-masterd/security/profile.php';
    public const string ACTION_PROFILE_DELETE = '/ecommerce-masterd/security/delete-profile.php';
}

?>