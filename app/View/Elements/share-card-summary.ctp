<footer class="footer-share-card-summary lead">
    <?php
        $totalPlaces = $share['places'];
        $participationCount = $share['participation_count'];
        $placesLeft = $totalPlaces - $participationCount;

        $priceLabel = 'euros';
        if ($share['price'] <= 1.0) {
            $priceLabel = 'euros';
        }
    ?>
    <?php if ($placesLeft > 1) : ?>

        <strong><?php echo $placesLeft; ?></strong> places

    <?php elseif ($placesLeft > 0) : ?>

        <strong><?php echo $placesLeft; ?></strong> place

    <?php else : ?>

        Complet

    <?php endif; ?>

    à <strong><?php echo number_format($share['price'], 1, '.', ''); ?></strong> <?php echo $priceLabel; ?>
</footer>