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
            <section class="mb-10 pb-8 border-b border-gray-300">
                <h2 class="text-2xl font-semibold mb-4">Engine Carbon Cleaning</h2>
                <p class="mb-4">
                    Our comprehensive engine carbon cleaning service uses professional decarbonisation equipment and detergents to decarbonise your engine, intake, and combustion areas. This removes built-up carbon deposits that restrict airflow, reduce power, and decrease fuel efficiency.
                </p>
                <h3 class="text-lg font-semibold mb-2">Common symptoms:</h3>
                <ul class="list-disc list-inside mb-4 space-y-1">
                    <li>Reduced MPG</li>
                    <li>Sluggish acceleration</li>
                    <li>Rough idle</li>
                    <li>Loss of power</li>
                    <li>Increased emissions</li>
                </ul>
                <p class="text-eco-green font-semibold">
                    Call 07706 230867 to ask about this service for your vehicle.
                </p>
            </section>

            <!-- DPF Cleaning -->
            <section class="mb-10 pb-8 border-b border-gray-300">
                <h2 class="text-2xl font-semibold mb-4">DPF Cleaning</h2>
                <p class="mb-4">
                    Diesel Particulate Filters (DPFs) trap soot particles from exhaust gases. When blocked, they trigger warning lights and can cause limp mode. Our DPF cleaning service restores blocked filters and prevents costly replacements.
                </p>
                <h3 class="text-lg font-semibold mb-2">Common symptoms:</h3>
                <ul class="list-disc list-inside mb-4 space-y-1">
                    <li>DPF warning light</li>
                    <li>Limp mode activation</li>
                    <li>Reduced power output</li>
                    <li>Excessive exhaust smoke</li>
                    <li>Dealer recommending DPF replacement</li>
                </ul>
                <p class="text-eco-green font-semibold">
                    Call 07706 230867 to ask about this service for your vehicle.
                </p>
            </section>

            <!-- EGR Valve Cleaning -->
            <section class="mb-10 pb-8 border-b border-gray-300">
                <h2 class="text-2xl font-semibold mb-4">EGR Valve Cleaning</h2>
                <p class="mb-4">
                    Exhaust Gas Recirculation (EGR) valves recirculate exhaust gases to reduce emissions. Over time, carbon deposits cause them to stick or become restricted, leading to poor performance and increased emissions. Our cleaning service restores proper operation.
                </p>
                <h3 class="text-lg font-semibold mb-2">Common symptoms:</h3>
                <ul class="list-disc list-inside mb-4 space-y-1">
                    <li>EGR warning light</li>
                    <li>Rough idle or stalling</li>
                    <li>Poor acceleration</li>
                    <li>Increased emissions</li>
                    <li>Engine hesitation</li>
                </ul>
                <p class="text-eco-green font-semibold">
                    Call 07706 230867 to ask about this service for your vehicle.
                </p>
            </section>

            <!-- Injector Cleaning & Diagnostics -->
            <section class="mb-10">
                <h2 class="text-2xl font-semibold mb-4">Injector Cleaning & Diagnostics</h2>
                <p class="mb-4">
                    Dirty or clogged fuel injectors cause poor spray patterns, leading to incomplete combustion. Our injector cleaning and diagnostics service identifies and resolves issues causing rough idle, poor performance, and fuel economy problems.
                </p>
                <h3 class="text-lg font-semibold mb-2">Common symptoms:</h3>
                <ul class="list-disc list-inside mb-4 space-y-1">
                    <li>Rough idle</li>
                    <li>Poor spray patterns</li>
                    <li>Reduced MPG</li>
                    <li>Engine misfires</li>
                    <li>Loss of power under load</li>
                </ul>
                <p class="text-eco-green font-semibold">
                    Call 07706 230867 to ask about this service for your vehicle.
                </p>
            </section>
        </article>
    </div>
</main>

<?php
get_footer();

