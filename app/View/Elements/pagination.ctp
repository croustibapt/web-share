<?php if ($results['total_pages'] > 1) : ?>

    <nav class="text-center">
        <ul class="pagination">

            <!-- Previous -->
            <?php if ($results['page'] > 1) : ?>

                <li>
                    <?php
                        echo $this->Html->link('<span aria-hidden="true">&laquo;</span>', $baseUrl.'page='.($results['page'] - 1), array(
                            'escape' => false,
                            'aria-label' => 'Previous'
                        ));
                    ?>
                </li>

            <?php else : ?>

                <li class="disabled">
                    <span aria-hidden="true">&laquo;</span>
                </li>

            <?php endif; ?>

            <?php for ($i = 1; $i <= $results['total_pages']; $i++) : ?>

                <!-- Middle -->
                <?php if ($i == $results['page']) : ?>

                    <li class="active">
                        <a href="#"><?php echo $i; ?></a>
                    </li>

                <?php else : ?>

                    <li>
                        <?php
                            echo $this->Html->link($i, $baseUrl.'page='.$i);
                        ?>

                    </li>

                <?php endif; ?>

            <?php endfor; ?>

            <!-- Next -->
            <?php if ($results['page'] < $results['total_pages']) : ?>

                <li>
                    <?php
                        echo $this->Html->link('<span aria-hidden="true">&raquo;</span>', $baseUrl.'page='.($results['page'] + 1), array(
                            'escape' => false,
                            'aria-label' => 'Next'
                        ));
                    ?>
                </li>

            <?php else : ?>

                <li class="disabled">
                    <span aria-hidden="true">&raquo;</span>
                </li>

            <?php endif; ?>

        </ul>
    </nav>

<?php endif; ?>