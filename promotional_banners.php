<?php if (!empty($banners)): ?>
    <section class="promo-banner">
        <div id="bannersCarousel" class="carousel slide" data-bs-ride="carousel">
            <?php if (count($banners) > 1): ?>
                <!-- Indicators -->
                <div class="carousel-indicators">
                    <?php foreach ($banners as $index => $banner): ?>
                        <button type="button" data-bs-target="#bannersCarousel" data-bs-slide-to="<?= $index ?>"
                            class="<?= $index === 0 ? 'active' : '' ?>"
                            aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                            aria-label="Slide <?= $index + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Carousel Items -->
            <div class="carousel-inner">
                <?php foreach ($banners as $index => $banner): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <a href="<?= htmlspecialchars($banner['link'] ?? '#') ?>">
                            <img src="./admin/uploads/banners/<?= htmlspecialchars($banner['image']) ?>"
                                class="d-block w-100 img-fluid"
                                alt="<?= htmlspecialchars($banner['title']) ?>"
                                loading="lazy"
                                onerror="this.src='https://coffective.com/wp-content/uploads/2018/06/default-featured-image.png.jpg';">
                        </a>
                        <?php if (!empty($banner['title'])): ?>
                            <div class="carousel-caption d-none d-md-block">
                                <h5><?= htmlspecialchars($banner['title']) ?></h5>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (count($banners) > 1): ?>
                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#bannersCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bannersCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>