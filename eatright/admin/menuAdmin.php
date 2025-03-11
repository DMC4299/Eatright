<?php
if (isset($_SESSION['admin'])) {
    ?>
    <header>
        <div class="logo">
            <h1><a href="../index.php">EatRight</a></h1>
        </div>
        <nav>
            <ul>
                <li><a href="admin.php">Alimentos</a></li>
                <li><a href="usuarios.php">Usuarios</a></li>
            </ul>
        </nav>
    </header>
    <?php
}
?>