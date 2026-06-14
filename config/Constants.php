<?php
class AppConfigConst
{

    public const string DB_HOST = 'localhost';
    public const string DB_NAME = 'ecommerce';
    public const string DB_USER = 'root';
    public const string DB_PASS = '';

    public const string PATH_HEADER = 'includes/header.php';
    public const string PATH_HEADER_BLANK = 'includes/header-blank.php';
    public const string PATH_FOOTER = 'includes/footer.html';
    public const string PATH_FOOTER_BLANK = 'includes/footer-blank.html';
    public const string PATH_MODAL = 'includes/modal.html';
    public const string PATH_CSS = 'assets/css/style.css';

    public const string PATH_INDEX = 'index.php';
    public const string PATH_ABOUT = 'about.php';

    public const string PATH_PRODUCTS_DETAILS = 'product/product-details.html';
    public const string PATH_PRODUCTS_LIST = 'product/list-product.php';
    public const string PATH_PRODUCTS_LIST_SECTION = 'product/product-list-section.html';
    public const string PATH_PRODUCTS_UPLOADS = 'product/uploads/';
    public const string PATH_PRODUCTS_MANAGER = 'product/list-product-manager.php';
    public const string PATH_PRODUCTS_VIEW = 'product/view-product.php';
    public const string PATH_PRODUCTS_CREATE = 'product/create-product.php';
    public const string PATH_PRODUCTS_EDIT = 'product/edit-product.php';
    public const string PATH_PRODUCTS_DELETE = 'product/delete-product.php';

    public const string PATH_CART = 'cart/cart.php';
    public const string PATH_ADD_TO_CART = 'cart/add-to-cart.php';

    public const string PATH_ORDER_LIST = 'order/list-order.php';
    public const string PATH_ORDER_VIEW = 'order/view-order.php';
    public const string PATH_ORDER_CHECKOUT = 'order/checkout.php';

    public const string PATH_LOGIN = 'security/login.php';
    public const string PATH_LOGOUT = 'security/logout.php';
    public const string PATH_PROFILE_DETAILS = 'security/profile-details.html';
    public const string PATH_REGISTER = 'security/register.php';
    public const string PATH_PROFILE = 'security/profile.php';
    public const string ACTION_PROFILE_DELETE = 'security/delete-profile.php';
    
    public static function getBaseUrl(): string
    {
        if (php_sapi_name() === 'cli' || empty($_SERVER['HTTP_HOST'])) {
            return '';
        }

        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            ? 'https'
            : 'http';

        $host = $_SERVER['HTTP_HOST'];
        $projectFolder = basename(dirname(__DIR__));

        $base = $scheme . '://' . $host;
        if ($projectFolder !== '' && $projectFolder !== '.' && $projectFolder !== '\\') {
            $base .= '/' . $projectFolder;
        }

        return rtrim($base, '/');
    }

    public static function path(string $relative): string
    {
        $relative = '/' . ltrim($relative, '/');
        return self::getBaseUrl() . $relative;
    }

}

?>