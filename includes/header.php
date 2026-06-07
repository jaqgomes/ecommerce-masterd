<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($pageTitle ?? 'JShop') ?>
    </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/ecommerce-masterd/assets/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= AppConfigConst::PATH_INDEX ?>">
                <i class="bi bi-shop-window me-2"></i>
                JShop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <?php if (SessionService::isAdmin()): ?>
                            <a class="nav-link" href="<?= AppConfigConst::PATH_PRODUCTS_MANAGER ?>">
                                <i class="bi-shop-window me-1"></i>Produtos
                            </a>
                        <?php elseif (SessionService::isLoggedIn()): ?>
                            <a class="nav-link" href="<?= AppConfigConst::PATH_PRODUCTS_LIST ?>">
                                <i class="bi-shop-window me-1"></i>Produtos
                            </a>
                        <?php endif; ?>
                    </li>
                    <?php if (SessionService::isLoggedIn()): ?>
                        <a class="nav-link" href="<?= AppConfigConst::PATH_ORDER_LIST ?>">
                            <i class="bi bi-person-lines-fill me-1"></i>Encomendas
                        </a>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person me-1"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <?php if (SessionService::isLoggedIn()): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= AppConfigConst::PATH_PROFILE ?>">
                                        <i class="bi bi-person-lines-fill me-1"></i>
                                        <?= SessionService::getUsername() ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (!SessionService::isLoggedIn()): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= AppConfigConst::PATH_LOGIN ?>"><i
                                            class="bi bi-box-arrow-in-right me-1"></i></i>Login
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (!SessionService::isLoggedIn()): ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= AppConfigConst::PATH_REGISTER ?>">
                                        <i class="bi bi-plus-circle me-1"></i>Registrar
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (SessionService::isLoggedIn()): ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= AppConfigConst::PATH_LOGOUT ?>">
                                        <i class="bi bi-box-arrow-right me-1"></i>Sair
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <?php $cartCount = array_sum(array_column($_SESSION['cart'] ?? [], 'quantity')); ?>
                        <a class="nav-link cart-badge-link position-relative" href="<?= AppConfigConst::PATH_CART ?>"
                            aria-label="Carrinho">
                            <i class="bi bi-cart3"></i>
                            <?php if ($cartCount > 0): ?>
                                <span class="badge cart-count-badge">
                                    <?= $cartCount ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    </div>

    <main class="container my-4">