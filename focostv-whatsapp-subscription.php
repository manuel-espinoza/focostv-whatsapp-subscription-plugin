<?php
/*
Plugin Name: Focos TV WhatsApp Subscription Channel
Description: Agrega un botón de suscripción al canal de WhatsApp, configurable desde el panel de administración.
Version: 1.0
Author: Manuel Espinoza
*/

// Crear la página de configuración en el menú del administrador
function focostv_whatsapp_add_admin_menu()
{
    add_menu_page(
        'FOCOS WhatsApp Subscription Settings', // Titulo de la pagina
        'FOCOS WhatsApp Channel', // Titulo del menu
        'manage_options', // Capacidad
        'focostv-whatsapp-settings', // Slug del menu
        'focostv_whatsapp_settings_page', // Funcion para mostrar el contenido
        'dashicons-whatsapp'
    );
}
add_action('admin_menu', 'focostv_whatsapp_add_admin_menu');

// Mostrar el contenido de la pagina de configuracion
function focostv_whatsapp_settings_page()
{
    ?>
    <div class="wrap">
        <h1>Configuraci&oacute;n del Canal de WhatsApp</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('focostv_whatsapp_settings_group');
            do_settings_sections('focostv-whatsapp-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Registrar las opciones para el numero de WhatsApp
function focostv_whatsapp_register_settings()
{
    register_setting('focostv_whatsapp_settings_group', 'focostv_whatsapp_phone');

    add_settings_section(
        'focostv_whatsapp_settings_section',
        'Información del Canal de WhatsApp (Ingrese el numero de telefono de whatsapp para la suscripción al canal)',
        null,
        'focostv-whatsapp-settings'
    );

    add_settings_field(
        'focostv_whatsapp_phone',
        'Número de teléfono de WhatsApp del canal',
        'focostv_whatsapp_phone_callback',
        'focostv-whatsapp-settings',
        'focostv_whatsapp_settings_section'
    );
}
add_action('admin_init', 'focostv_whatsapp_register_settings');

// Callback para mostrar el campo del numero de telefono
function focostv_whatsapp_phone_callback()
{
    $phone = get_option('focostv_whatsapp_phone');
    ?>
    <input type="text" name="focostv_whatsapp_phone" value="<?php echo esc_attr($phone); ?>" style="width: 50%;">
    <?php
}

// Shortcode para mostrar el boton de suscripción
function focostv_whatsapp_channel_subscription()
{
    $phone = get_option('focostv_whatsapp_phone');

    if (!$phone) {
        return '<span style="display: none;">Canal de WhatsApp de FocosTV no configurado</span>';
    }

    ob_start();
    ?>
    <section class="focostv-whatsapp-subscription">
        <div class="focostv-whatsapp-subscription-icon">
            <i class="fa-brands fa-whatsapp"></i>
        </div>
        <div class="focostv-whatsapp-subscription-content">
            <h2 class="focostv-whatsapp-subscription-title">
                S&eacute; el primero en saberlo.
            </h2>
            <p class="focostv-whatsapp-subscription-text">
                &Uacute;nete a nuestro canal de WhatsApp para recibir todas las actualizaciones y novedades al instante,
                directamente en tu tel&eacute;fono.
            </p>
        </div>
        <div class="focostv-whatsapp-subscription-button-container">
            <a href="https://wa.me/<?php echo esc_attr($phone); ?>" target="_blank" class="focostv-whatsapp-subscription-button">¡Unirme
                al canal gratis!</a>
        </div>


    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('focostv_whatsapp_subscription', 'focostv_whatsapp_channel_subscription');

// Enqueue CSS para el botón
function focostv_enqueue_styles()
{
    wp_enqueue_style('focostv-whatsapp-subscription-style', plugins_url('style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'focostv_enqueue_styles');
