<section class="destacados-container">
    <div class="page-centered">

        <div class="destacados-header">
            <span>ESTANCIAS EXCLUSIVAS</span>
            <h2>Nuestras Propiedades Destacadas</h2>
            <div class="divider"></div>
        </div>

        <div class="villas-grid">
            <?php if (!empty($propiedadesDestacadas)): ?>
                <?php foreach ($propiedadesDestacadas as $v): ?>
                    <div class="villa-card">
                        <div class="villa-image">
                            <img
                                src="<?php echo e($v['foto_principal'] ?? 'assets/images/no-image.jpg'); ?>"
                                alt="<?php echo e($v['nombre'] ?? 'Propiedad'); ?>"
                            >
                            <span class="badge">Disponible</span>
                        </div>

                        <div class="villa-info">
                            <h3><?php echo e($v['nombre'] ?? 'Sin nombre'); ?></h3>

                            <?php if (!empty($v['ubicacion'])): ?>
                                <p class="ubicacion-text">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo e($v['ubicacion']); ?>
                                </p>
                            <?php endif; ?>

                            <div class="villa-icons">
                                <span>
                                    <i class="fas fa-bed"></i>
                                    <?php echo (int)($v['recamaras'] ?? 0); ?> Rec.
                                </span>

                                <span>
                                    <i class="fas fa-bath"></i>
                                    <?php echo (int)($v['banos'] ?? 0); ?> Baños
                                </span>

                                <span>
                                    <i class="fas fa-users"></i>
                                    <?php echo (int)($v['capacidad'] ?? 0); ?> Pers.
                                </span>
                            </div>

                            <div class="villa-footer">
                                <a href="propiedad.php?id=<?php echo (int)$v['id']; ?>" class="btn-explorar">
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Por el momento todas nuestras propiedades están reservadas. ¡Vuelve pronto!</p>
            <?php endif; ?>
        </div>

    </div>
</section>