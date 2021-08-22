<!-- Footer -->
<footer id="footer">
    <div class="footer-content p-b-50">
        <div class="container">
            <div class="row">

                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-3">
                            <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('footer1')) : else : endif; ?>
                        </div>
                        <div class="col-lg-3">
                            <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('footer2')) : else : endif; ?>
                        </div>
                        <div class="col-lg-3">
                            <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('footer3')) : else : endif; ?>
                        </div>
                        <div class="col-lg-3">
                            <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('footer4')) : else : endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('footer5')) : else : endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">

                    <div class="social-icons social-icons-colored float-left">
                        <ul>

                            <li class="social-instagram"><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li class="social-youtube"><a href="#"><i class="fab fa-youtube"></i></a></li>

                        </ul>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="copyright-text text-center">Copyright 2020 © Developed By Insmart</div>
                </div>
            </div>
        </div>
    </div>

</footer>
<!-- end: Footer -->
</div>
<!-- end: Body Inner -->
<!-- Scroll top -->
<a id="scrollTop"><i class="icon-chevron-up"></i><i class="icon-chevron-up"></i></a>
<!--Plugins-->
<?php
wp_footer();
?>
</body>

</html>