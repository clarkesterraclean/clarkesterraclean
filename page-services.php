<?php
/**
 * Template Name: Services
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
            <p class="mb-8">
                We take a diagnostics-first approach to identify the root cause of your vehicle's performance issues. Our services use professional engine decarbonisation service using specialist equipment and detergents to thoroughly clean and restore your engine and emissions systems.
            </p>

            <!-- Engine Carbon Cleaning -->
            <section class="mb-12 pb-8 border-b border-gray-300">
                <div class="grid md:grid-cols-2 gap-8 items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">Engine Carbon Cleaning</h2>
                        <p class="mb-4 leading-relaxed">
                            Our comprehensive engine carbon cleaning service uses professional decarbonisation equipment and detergents to decarbonise your engine, intake, and combustion areas. This removes built-up carbon deposits that restrict airflow, reduce power, and decrease fuel efficiency.
                        </p>
                        <h3 class="text-lg font-semibold mb-3 text-eco-green">Common symptoms:</h3>
                        <ul class="list-disc list-inside mb-4 space-y-2">
                            <li>Reduced MPG</li>
                            <li>Sluggish acceleration</li>
                            <li>Rough idle</li>
                            <li>Loss of power</li>
                            <li>Increased emissions</li>
                        </ul>
                    </div>
                    <?php 
                    $service1_video_id = get_theme_mod('service_1_video', '');
                    $service1_image_id = get_theme_mod('service_1_image', '');
                    
                    if ($service1_video_id) {
                        $service1_video_url = wp_get_attachment_url($service1_video_id);
                        if ($service1_video_url) {
                            echo '<div class="h-64 overflow-hidden rounded-lg">';
                            echo '<video class="w-full h-full object-cover" controls playsinline>';
                            echo '<source src="' . esc_url($service1_video_url) . '" type="' . esc_attr(get_post_mime_type($service1_video_id)) . '">';
                            echo 'Your browser does not support the video tag.';
                            echo '</video>';
                            echo '</div>';
                        }
                    } elseif ($service1_image_id) {
                        echo '<div class="order-first md:order-last">';
                        echo wp_get_attachment_image($service1_image_id, 'medium', false, array('class' => 'rounded-lg shadow-lg w-full h-auto'));
                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="bg-eco-green/10 rounded-lg p-4 border-l-4 border-eco-green">
                    <p class="text-eco-green font-semibold">
                        Call 07706 230867 to ask about this service for your vehicle.
                    </p>
                </div>
            </section>

            <!-- DPF Cleaning -->
            <section class="mb-12 pb-8 border-b border-gray-300">
                <div class="grid md:grid-cols-2 gap-8 items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">DPF Cleaning</h2>
                        <p class="mb-4 leading-relaxed">
                            Diesel Particulate Filters (DPFs) trap soot particles from exhaust gases. When blocked, they trigger warning lights and can cause limp mode. Our DPF cleaning service restores blocked filters and prevents costly replacements – saving you thousands compared to dealer recommendations.
                        </p>
                        <h3 class="text-lg font-semibold mb-3 text-eco-green">Common symptoms:</h3>
                        <ul class="list-disc list-inside mb-4 space-y-2">
                            <li>DPF warning light</li>
                            <li>Limp mode activation</li>
                            <li>Reduced power output</li>
                            <li>Excessive exhaust smoke</li>
                            <li>Dealer recommending DPF replacement</li>
                        </ul>
                    </div>
                    <?php 
                    $service2_video_id = get_theme_mod('service_2_video', '');
                    $service2_image_id = get_theme_mod('service_2_image', '');
                    
                    if ($service2_video_id) {
                        $service2_video_url = wp_get_attachment_url($service2_video_id);
                        if ($service2_video_url) {
                            echo '<div class="h-64 overflow-hidden rounded-lg">';
                            echo '<video class="w-full h-full object-cover" controls playsinline>';
                            echo '<source src="' . esc_url($service2_video_url) . '" type="' . esc_attr(get_post_mime_type($service2_video_id)) . '">';
                            echo 'Your browser does not support the video tag.';
                            echo '</video>';
                            echo '</div>';
                        }
                    } elseif ($service2_image_id) {
                        echo '<div class="order-first md:order-last">';
                        echo wp_get_attachment_image($service2_image_id, 'medium', false, array('class' => 'rounded-lg shadow-lg w-full h-auto'));
                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="bg-eco-green/10 rounded-lg p-4 border-l-4 border-eco-green">
                    <p class="text-eco-green font-semibold">
                        Call 07706 230867 to ask about this service for your vehicle.
                    </p>
                </div>
            </section>

            <!-- EGR Valve Cleaning -->
            <section class="mb-12 pb-8 border-b border-gray-300">
                <div class="grid md:grid-cols-2 gap-8 items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">EGR Valve Cleaning</h2>
                        <p class="mb-4 leading-relaxed">
                            Exhaust Gas Recirculation (EGR) valves recirculate exhaust gases to reduce emissions. Over time, carbon deposits cause them to stick or become restricted, leading to poor performance and increased emissions. Our cleaning service restores proper operation without the need for replacement.
                        </p>
                        <h3 class="text-lg font-semibold mb-3 text-eco-green">Common symptoms:</h3>
                        <ul class="list-disc list-inside mb-4 space-y-2">
                            <li>EGR warning light</li>
                            <li>Rough idle or stalling</li>
                            <li>Poor acceleration</li>
                            <li>Increased emissions</li>
                            <li>Engine hesitation</li>
                        </ul>
                    </div>
                    <?php 
                    $service3_video_id = get_theme_mod('service_3_video', '');
                    $service3_image_id = get_theme_mod('service_3_image', '');
                    
                    if ($service3_video_id) {
                        $service3_video_url = wp_get_attachment_url($service3_video_id);
                        if ($service3_video_url) {
                            echo '<div class="h-64 overflow-hidden rounded-lg">';
                            echo '<video class="w-full h-full object-cover" controls playsinline>';
                            echo '<source src="' . esc_url($service3_video_url) . '" type="' . esc_attr(get_post_mime_type($service3_video_id)) . '">';
                            echo 'Your browser does not support the video tag.';
                            echo '</video>';
                            echo '</div>';
                        }
                    } elseif ($service3_image_id) {
                        echo '<div class="order-first md:order-last">';
                        echo wp_get_attachment_image($service3_image_id, 'medium', false, array('class' => 'rounded-lg shadow-lg w-full h-auto'));
                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="bg-eco-green/10 rounded-lg p-4 border-l-4 border-eco-green">
                    <p class="text-eco-green font-semibold">
                        Call 07706 230867 to ask about this service for your vehicle.
                    </p>
                </div>
            </section>

            <!-- Injector Cleaning & Diagnostics -->
            <section class="mb-12">
                <div class="grid md:grid-cols-2 gap-8 items-start mb-6">
                    <div>
                        <h2 class="text-2xl font-semibold mb-4">Injector Cleaning & Diagnostics</h2>
                        <p class="mb-4 leading-relaxed">
                            Dirty or clogged fuel injectors cause poor spray patterns, leading to incomplete combustion. Our injector cleaning and diagnostics service identifies and resolves issues causing rough idle, poor performance, and fuel economy problems.
                        </p>
                        <h3 class="text-lg font-semibold mb-3 text-eco-green">Common symptoms:</h3>
                        <ul class="list-disc list-inside mb-4 space-y-2">
                            <li>Rough idle</li>
                            <li>Poor spray patterns</li>
                            <li>Reduced MPG</li>
                            <li>Engine misfires</li>
                            <li>Loss of power under load</li>
                        </ul>
                    </div>
                    <?php 
                    $service4_video_id = get_theme_mod('service_4_video', '');
                    $service4_image_id = get_theme_mod('service_4_image', '');
                    
                    if ($service4_video_id) {
                        $service4_video_url = wp_get_attachment_url($service4_video_id);
                        if ($service4_video_url) {
                            echo '<div class="h-64 overflow-hidden rounded-lg">';
                            echo '<video class="w-full h-full object-cover" controls playsinline>';
                            echo '<source src="' . esc_url($service4_video_url) . '" type="' . esc_attr(get_post_mime_type($service4_video_id)) . '">';
                            echo 'Your browser does not support the video tag.';
                            echo '</video>';
                            echo '</div>';
                        }
                    } elseif ($service4_image_id) {
                        echo '<div class="order-first md:order-last">';
                        echo wp_get_attachment_image($service4_image_id, 'medium', false, array('class' => 'rounded-lg shadow-lg w-full h-auto'));
                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="bg-eco-green/10 rounded-lg p-4 border-l-4 border-eco-green">
                    <p class="text-eco-green font-semibold">
                        Call 07706 230867 to ask about this service for your vehicle.
                    </p>
                </div>
            </section>
        </article>
    </div>
</main>

<?php
get_footer();

