<!DOCTYPE html>
<html lang="en">

<head>
    <title>Contact Us Form</title>
    <?php Core\Utils::partial('head'); ?>
</head>

<body>
    <!-- Header Start -->

    <?php Core\Utils::partial('header'); ?>


    <!-- Header End -->
    <section>
        <div class="container">
             <h2 class="h2content-subtitle">Contact Us</h2>
                
                <div class="inquiry-form-box">
                    <div class="form-box">

                        <form class="inquiry-form" method="post" action="/inquiry" id="get-quote-form" name='inquity_form'>
                            <div class="input-box left-view">
                                <input type="text" name="name" placeholder="Your Name*">
                            </div>
                            <div class="input-box right-view">
                                <input type="email" name="email" placeholder="Your Email*">
                            </div>
                            <div class="input-box left-view">
                                <input type="tel" name="phone_number" placeholder="Phone Number*">
                            </div>

                            <div class="input-box right-view">
                                <select required name="category">
                                    <option value="">Category</option>
                                    <option value="web_development">Web Development</option>
                                    <option value="app_development">Mobile App Development</option>
                                    <option value="graphics">Graphics Designing</option>
                                    <option value="seo">SEO Services</option>
                                </select>
                            </div>
                            <div class="input-box">
                                <textarea name="message" placeholder="Message*"></textarea>
                            </div>
                            <?php if(Core\Config::get("gcaptcha.enable")){ ?>
                            <div class="capcha_box input-box">
                                <div id="gcapcha-contactus"></div>
                                <div class="capcha_hide_box">
                                    <input type="hidden" value="" name="capcha_valiation" />
                                </div>
                            </div>
                            <?php } ?>
                            <button type="submit" class="btn" id="submit-button">Send Your Inquiry</button>
                        </form>


                    </div>
                </div>
        </div>
    </section>
    <!-- Footer start -->
    <?php Core\Utils::partial('footer'); ?>
    <!-- Footer end -->
</body>

</html>