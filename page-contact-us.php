<?php
/**
 * Template Name: Contact Us
 *
 * @package ClarkesTerraClean
 */

get_header();
?>

<main class="py-16 md:py-24 bg-carbon-light text-text-dark">
    <div class="max-w-4xl mx-auto px-4 space-y-8">
        <header>
            <h1 class="text-3xl md:text-4xl font-semibold mb-4"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <p class="text-gray-700"><?php the_excerpt(); ?></p>
            <?php endif; ?>
        </header>

        <article class="prose prose-neutral max-w-none">
            <!-- Phone CTA -->
            <div class="text-center mb-10">
                <?php 
                $phone = get_theme_mod('business_phone', '07706 230867');
                $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                ?>
                <a href="tel:<?php echo esc_attr($phone_clean); ?>" class="inline-block border border-eco-green text-eco-green rounded-full px-8 py-4 text-lg font-semibold hover:bg-eco-green hover:text-carbon-dark transition">
                    📞 Call <?php echo esc_html($phone); ?>
                </a>
            </div>

            <p class="mb-8 text-center text-lg">
                Call, text or WhatsApp to book. Most appointments arranged within a few days; limp-mode issues prioritised.
            </p>

            <!-- Contact Form -->
            <form data-contact-form method="post" class="bg-white border border-eco-green/30 rounded-lg p-8 space-y-6">
                <input type="hidden" name="nonce" value="<?php echo esc_attr(wp_create_nonce('clarkes_contact_nonce')); ?>">
                <input type="text" name="company" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">
                
                <div>
                    <label for="contact-name" class="block text-text-dark font-medium mb-2">Name *</label>
                    <input type="text" id="contact-name" name="name" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded text-text-dark focus:outline-none focus:border-eco-green" placeholder="Your full name">
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>

                <div>
                    <label for="contact-phone" class="block text-text-dark font-medium mb-2">Phone *</label>
                    <input type="tel" id="contact-phone" name="phone" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded text-text-dark focus:outline-none focus:border-eco-green" placeholder="07700 000000">
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>

                <div>
                    <label for="contact-email" class="block text-text-dark font-medium mb-2">Email</label>
                    <input type="email" id="contact-email" name="email" class="w-full px-4 py-2 bg-white border border-gray-300 rounded text-text-dark focus:outline-none focus:border-eco-green" placeholder="your.email@example.com">
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>

                <div>
                    <label for="contact-vehicle" class="block text-text-dark font-medium mb-2">Vehicle (make/model/engine) *</label>
                    <input type="text" id="contact-vehicle" name="vehicle" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded text-text-dark focus:outline-none focus:border-eco-green" placeholder="e.g. Ford Focus 1.6 TDCi">
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>

                <div>
                    <label for="contact-issue" class="block text-text-dark font-medium mb-2">Issue/Symptom *</label>
                    <textarea id="contact-issue" name="issue" rows="4" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded text-text-dark focus:outline-none focus:border-eco-green" placeholder="e.g. Warning light, poor MPG, limp mode, rough idle..."></textarea>
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>

                <div>
                    <label for="contact-method" class="block text-text-dark font-medium mb-2">Preferred Contact Method *</label>
                    <select id="contact-method" name="contact_method" required class="w-full px-4 py-2 bg-white border border-gray-300 rounded text-text-dark focus:outline-none focus:border-eco-green">
                        <option value="">Please select...</option>
                        <option value="phone">Phone</option>
                        <option value="email">Email</option>
                        <option value="text">Text Message</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>

                <div>
                    <label for="contact-message" class="block text-text-dark font-medium mb-2">Message</label>
                    <textarea id="contact-message" name="message" rows="4" class="w-full px-4 py-2 bg-white border border-gray-300 rounded text-text-dark focus:outline-none focus:border-eco-green" placeholder="Any additional information about your vehicle or preferred appointment times..."></textarea>
                    <p class="error-message text-red-600 text-sm mt-1 hidden"></p>
                </div>

                <div class="text-center">
                    <button type="submit" class="border border-eco-green text-eco-green rounded-full px-8 py-3 text-base font-semibold hover:bg-eco-green hover:text-carbon-dark transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span class="submit-text">Send Enquiry</span>
                    </button>
                    <p class="error-message text-red-600 text-sm mt-2 hidden"></p>
                </div>

                <p class="text-center text-text-dark text-sm mt-6">
                    We'll respond as quickly as possible to confirm availability and pricing for your vehicle.
                </p>
            </form>
        </article>
    </div>
</main>

<?php
get_footer();

