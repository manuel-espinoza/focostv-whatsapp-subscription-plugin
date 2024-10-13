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
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="60" height="60"
                viewBox="0 0 60 60" fill="none">
                <g clip-path="url(#clip0_673_1083)">
                    <rect width="60" height="60" fill="url(#pattern0_673_1083)" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M45.7469 14.5102C41.7354 10.494 36.4006 8.28126 30.7168 8.27881C19.0055 8.27881 9.47378 17.8099 9.46907 29.5249C9.46764 33.2697 10.4458 36.9249 12.3053 40.1473L9.29102 51.1574L20.5547 48.2028C23.6581 49.8955 27.1522 50.7878 30.7083 50.7891H30.717C42.4272 50.7891 51.9597 41.2567 51.9646 29.5415C51.9667 23.8645 49.7584 18.5262 45.7469 14.5102ZM30.717 47.2007H30.7099C27.541 47.1995 24.4329 46.348 21.7215 44.739L21.0766 44.3565L14.3925 46.1097L16.1766 39.5929L15.7567 38.9244C13.989 36.1129 13.0552 32.8631 13.0566 29.526C13.0605 19.789 20.9827 11.8674 30.7242 11.8674C35.441 11.869 39.8753 13.7082 43.2095 17.0466C46.5438 20.3847 48.3787 24.8216 48.3769 29.5405C48.373 39.2783 40.4507 47.2007 30.717 47.2007ZM40.4039 33.9739C39.873 33.7084 37.2631 32.424 36.7763 32.2467C36.2898 32.0699 35.9357 31.9812 35.5818 32.5127C35.228 33.0442 34.2103 34.2399 33.9006 34.5944C33.5909 34.9485 33.2812 34.993 32.7505 34.7271C32.2194 34.4615 30.5089 33.9008 28.481 32.092C26.9028 30.6843 25.8373 28.9458 25.5276 28.4141C25.2179 27.8826 25.4945 27.5953 25.7605 27.3306C25.9992 27.0926 26.2914 26.7104 26.5569 26.4004C26.8225 26.0905 26.9108 25.8689 27.0878 25.5147C27.2649 25.1604 27.1763 24.8503 27.0435 24.5847C26.9108 24.3187 25.849 21.7055 25.4066 20.6425C24.9756 19.6075 24.538 19.7475 24.2121 19.7311C23.9028 19.7158 23.5485 19.7123 23.1945 19.7123C22.8406 19.7123 22.2655 19.8452 21.7788 20.3767C21.2922 20.9082 19.9207 22.1927 19.9207 24.8059C19.9207 27.4191 21.8229 29.9434 22.0885 30.2979C22.3538 30.6522 25.8322 36.0146 31.1578 38.3144C32.4242 38.8614 33.4133 39.1879 34.1844 39.4328C35.4563 39.837 36.6136 39.7797 37.5282 39.6432C38.5483 39.4907 40.6693 38.3587 41.1117 37.1187C41.5541 35.8784 41.554 34.8154 41.4214 34.594C41.2887 34.3728 40.9348 34.2399 40.4039 33.9739Z"
                        fill="white" />
                </g>
                <defs>
                    <pattern id="pattern0_673_1083" patternContentUnits="objectBoundingBox" width="1" height="1">
                        <use xlink:href="#image0_673_1083" transform="scale(0.00170068 0.00169492)" />
                    </pattern>
                    <clipPath id="clip0_673_1083">
                        <rect width="60" height="60" fill="white" />
                    </clipPath>
                    <image id="image0_673_1083" width="588" height="590"
                        xlink:href="data:image/png;base64,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" />
                </defs>
            </svg>
        </div>
        <div class="focostv-whatsapp-subscription-content">
            <h2 class="focostv-whatsapp-subscription-title">
                S&eacute; el primero en saberlo.
            </h2>
            <p class="focostv-whatsapp-subscription-text">
                &Uacute;nete a nuestro <a href="https://wa.me/<?php echo esc_attr($phone); ?>">canal de WhatsApp</a> para
                recibir todas las actualizaciones y novedades al instante,
                directamente en tu tel&eacute;fono.
            </p>
        </div>
        <div class="focostv-whatsapp-subscription-button-container">
            <a href="https://wa.me/<?php echo esc_attr($phone); ?>" target="_blank"
                class="focostv-whatsapp-subscription-button">¡Unirme
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
