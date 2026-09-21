<?php
/**
 * One-time, idempotent WordPress content/bootstrap script.
 *
 * Run with WP-CLI:
 * wp eval-file wp-content/themes/project-theme/tools/bootstrap-wordpress.php
 *
 * @package TowerExchange
 */

if (! defined('ABSPATH')) {
    exit(1);
}

if (! function_exists('acf_import_field_group')) {
    WP_CLI::error('ACF Pro must be active before running the Tower Exchange bootstrap.');
}

function tower_bootstrap_field(string $key, string $label, string $name, string $type, array $extra = array()): array
{
    return array_merge(
        array(
            'key'               => 'field_tower_' . $key,
            'label'             => $label,
            'name'              => $name,
            'type'              => $type,
            'instructions'      => '',
            'required'          => 0,
            'conditional_logic' => 0,
            'wrapper'           => array('width' => '', 'class' => '', 'id' => ''),
        ),
        $extra
    );
}

function tower_bootstrap_message(string $key, string $label): array
{
    return tower_bootstrap_field(
        $key,
        $label,
        'message_' . $key,
        'message',
        array(
            'message' => '<strong>' . esc_html($label) . '</strong>',
            'new_lines' => 'wpautop',
            'esc_html' => 0,
            'wrapper' => array('width' => '100', 'class' => 'tower-field-heading', 'id' => ''),
        )
    );
}

function tower_bootstrap_disable(string $key): array
{
    return tower_bootstrap_field(
        $key . '_disable',
        'Вимкнути блок',
        'disable_block',
        'true_false',
        array('ui' => 1, 'default_value' => 0, 'wrapper' => array('width' => '20', 'class' => '', 'id' => ''))
    );
}

function tower_bootstrap_text(string $key, string $label, string $name, string $width = ''): array
{
    return tower_bootstrap_field(
        $key,
        $label,
        $name,
        'text',
        array('default_value' => '', 'maxlength' => '', 'placeholder' => '', 'prepend' => '', 'append' => '', 'wrapper' => array('width' => $width, 'class' => '', 'id' => ''))
    );
}

function tower_bootstrap_textarea(string $key, string $label, string $name, string $width = ''): array
{
    return tower_bootstrap_field(
        $key,
        $label,
        $name,
        'textarea',
        array('rows' => 4, 'new_lines' => '', 'wrapper' => array('width' => $width, 'class' => '', 'id' => ''))
    );
}

function tower_bootstrap_link_field(string $key, string $label, string $name, string $width = ''): array
{
    return tower_bootstrap_field(
        $key,
        $label,
        $name,
        'link',
        array('return_format' => 'array', 'wrapper' => array('width' => $width, 'class' => '', 'id' => ''))
    );
}

function tower_bootstrap_select(string $key, string $label, string $name, array $choices, string $width = ''): array
{
    return tower_bootstrap_field(
        $key,
        $label,
        $name,
        'select',
        array('choices' => $choices, 'default_value' => array_key_first($choices), 'return_format' => 'value', 'ui' => 1, 'wrapper' => array('width' => $width, 'class' => '', 'id' => ''))
    );
}

function tower_bootstrap_repeater(string $key, string $label, string $name, array $sub_fields, string $button_label = 'Додати елемент'): array
{
    return tower_bootstrap_field(
        $key,
        $label,
        $name,
        'repeater',
        array(
            'layout'       => 'block',
            'pagination'   => 0,
            'min'          => 0,
            'max'          => 0,
            'collapsed'    => '',
            'button_label' => $button_label,
            'rows_per_page'=> 20,
            'sub_fields'   => $sub_fields,
        )
    );
}

function tower_bootstrap_layout(string $key, string $name, string $label, array $sub_fields): array
{
    return array(
        'key'        => 'layout_tower_' . $key,
        'name'       => $name,
        'label'      => $label,
        'display'    => 'block',
        'sub_fields' => $sub_fields,
        'min'        => '',
        'max'        => '',
    );
}

$options_group = array(
    'key' => 'group_tower_options',
    'title' => 'Tower Exchange — глобальні налаштування',
    'fields' => array(
        tower_bootstrap_field('options_header_tab', 'Header', '', 'tab', array('placement' => 'top', 'endpoint' => 0)),
        tower_bootstrap_message('options_brand_message', 'Бренд і логотипи'),
        tower_bootstrap_field('logo_light', 'Логотип для світлої теми', 'tower_logo_light', 'image', array('return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'wrapper' => array('width' => '50', 'class' => '', 'id' => ''))),
        tower_bootstrap_field('logo_dark', 'Логотип для темної теми', 'tower_logo_dark', 'image', array('return_format' => 'id', 'preview_size' => 'medium', 'library' => 'all', 'wrapper' => array('width' => '50', 'class' => '', 'id' => ''))),
        tower_bootstrap_text('brand_name', 'Назва бренду', 'tower_brand_name', '50'),
        tower_bootstrap_text('brand_subtitle', 'Підпис бренду', 'tower_brand_subtitle', '50'),
        tower_bootstrap_message('options_header_cta_message', 'Кнопки Header'),
        tower_bootstrap_link_field('header_cta', 'Основна кнопка', 'tower_header_cta', '50'),
        tower_bootstrap_link_field('mobile_cta', 'Мобільна фіксована кнопка', 'tower_mobile_cta', '50'),
        tower_bootstrap_field('options_footer_tab', 'Footer', '', 'tab', array('placement' => 'top', 'endpoint' => 0)),
        tower_bootstrap_message('options_footer_message', 'Контакти й копірайт'),
        tower_bootstrap_text('footer_address_label', 'Підпис адреси', 'tower_footer_address_label', '33'),
        tower_bootstrap_text('footer_address', 'Адреса', 'tower_footer_address', '67'),
        tower_bootstrap_text('copyright', 'Копірайт', 'tower_copyright', '100'),
    ),
    'location' => array(
        array(array('param' => 'options_page', 'operator' => '==', 'value' => 'tower-exchange-settings')),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(),
    'active' => true,
    'description' => 'Глобальні дані Header і Footer.',
    'show_in_rest' => 0,
);

$hero_fields = array(
    tower_bootstrap_disable('hero'),
    tower_bootstrap_message('hero_heading_message', 'Заголовок і вступ'),
    tower_bootstrap_text('hero_eyebrow', 'Надзаголовок', 'eyebrow', '33'),
    tower_bootstrap_text('hero_title', 'Основний заголовок', 'title', '33'),
    tower_bootstrap_text('hero_subtitle', 'Підзаголовок', 'subtitle', '34'),
    tower_bootstrap_textarea('hero_lead', 'Вступний текст', 'lead', '100'),
    tower_bootstrap_link_field('hero_primary_link', 'Основна кнопка', 'primary_link', '50'),
    tower_bootstrap_link_field('hero_secondary_link', 'Друга кнопка', 'secondary_link', '50'),
    tower_bootstrap_message('hero_location_message', 'Адреса'),
    tower_bootstrap_text('hero_location_label', 'Підпис', 'location_label', '33'),
    tower_bootstrap_text('hero_location_text', 'Адреса', 'location_text', '67'),
    tower_bootstrap_message('hero_art_message', 'Фірмова графіка'),
    tower_bootstrap_text('hero_art_top_code', 'Верхній код', 'art_top_code', '15'),
    tower_bootstrap_text('hero_art_top_text', 'Верхній підпис', 'art_top_text', '35'),
    tower_bootstrap_text('hero_art_bottom_code', 'Нижній код', 'art_bottom_code', '15'),
    tower_bootstrap_text('hero_art_bottom_text', 'Нижній підпис', 'art_bottom_text', '35'),
    tower_bootstrap_text('hero_coordinates', 'Координати', 'coordinates', '100'),
    tower_bootstrap_message('hero_trust_message', 'Інформаційна смуга'),
    tower_bootstrap_repeater(
        'hero_trust_items',
        'Елементи смуги',
        'trust_items',
        array(
            tower_bootstrap_text('hero_trust_label', 'Підпис', 'label', '25'),
            tower_bootstrap_text('hero_trust_value', 'Значення', 'value', '35'),
            tower_bootstrap_link_field('hero_trust_link', 'Посилання замість тексту', 'link', '40'),
        ),
        'Додати елемент'
    ),
);

$calculator_fields = array(
    tower_bootstrap_disable('calculator'),
    tower_bootstrap_message('calculator_heading_message', 'Заголовок калькулятора'),
    tower_bootstrap_text('calculator_eyebrow', 'Надзаголовок', 'eyebrow', '33'),
    tower_bootstrap_text('calculator_title', 'Заголовок', 'title', '33'),
    tower_bootstrap_textarea('calculator_intro', 'Опис', 'intro', '34'),
    tower_bootstrap_message('calculator_labels_message', 'Підписи інтерфейсу'),
    tower_bootstrap_text('calculator_accent_primary', 'Технічний підпис 1', 'accent_primary', '25'),
    tower_bootstrap_text('calculator_accent_secondary', 'Технічний підпис 2', 'accent_secondary', '25'),
    tower_bootstrap_text('calculator_direction_one', 'Напрям 1', 'direction_crypto_cash', '25'),
    tower_bootstrap_text('calculator_direction_two', 'Напрям 2', 'direction_cash_crypto', '25'),
    tower_bootstrap_text('calculator_amount_label', 'Підпис суми', 'amount_label', '25'),
    tower_bootstrap_text('calculator_result_label', 'Підпис результату', 'result_label', '25'),
    tower_bootstrap_text('calculator_result_text', 'Текст результату', 'result_text', '25'),
    tower_bootstrap_text('calculator_note', 'Примітка', 'note', '25'),
    tower_bootstrap_link_field('calculator_cta', 'Кнопка менеджера', 'cta_link', '100'),
);

$services_fields = array(
    tower_bootstrap_disable('services'),
    tower_bootstrap_message('services_heading_message', 'Заголовок секції'),
    tower_bootstrap_text('services_eyebrow', 'Надзаголовок', 'eyebrow', '33'),
    tower_bootstrap_text('services_title', 'Заголовок', 'title', '33'),
    tower_bootstrap_text('services_title_accent', 'Акцентний рядок', 'title_accent', '34'),
    tower_bootstrap_message('services_items_message', 'Послуги'),
    tower_bootstrap_repeater(
        'services_items',
        'Список послуг',
        'items',
        array(
            tower_bootstrap_select('services_item_icon', 'Іконка', 'icon', array('swap' => 'Обмін', 'globe' => 'Глобус', 'card' => 'Переказ'), '20'),
            tower_bootstrap_text('services_item_title', 'Назва', 'title', '30'),
            tower_bootstrap_textarea('services_item_text', 'Опис', 'text', '50'),
            tower_bootstrap_link_field('services_item_link', 'Посилання', 'link', '100'),
        ),
        'Додати послугу'
    ),
);

$process_fields = array(
    tower_bootstrap_disable('process'),
    tower_bootstrap_message('process_heading_message', 'Опис процесу'),
    tower_bootstrap_text('process_eyebrow', 'Надзаголовок', 'eyebrow', '30'),
    tower_bootstrap_text('process_title', 'Заголовок', 'title', '70'),
    tower_bootstrap_textarea('process_intro', 'Вступ', 'intro', '60'),
    tower_bootstrap_link_field('process_cta', 'Кнопка', 'cta_link', '40'),
    tower_bootstrap_message('process_steps_message', 'Кроки'),
    tower_bootstrap_repeater(
        'process_steps',
        'Кроки процесу',
        'steps',
        array(
            tower_bootstrap_text('process_step_title', 'Заголовок', 'title', '35'),
            tower_bootstrap_textarea('process_step_text', 'Опис', 'text', '65'),
        ),
        'Додати крок'
    ),
);

$security_fields = array(
    tower_bootstrap_disable('security'),
    tower_bootstrap_message('security_heading_message', 'Основний текст'),
    tower_bootstrap_text('security_eyebrow', 'Надзаголовок', 'eyebrow', '30'),
    tower_bootstrap_text('security_title', 'Заголовок', 'title', '70'),
    tower_bootstrap_textarea('security_text', 'Опис', 'text', '60'),
    tower_bootstrap_link_field('security_cta', 'Кнопка', 'cta_link', '40'),
    tower_bootstrap_message('security_checks_message', 'Контрольні пункти'),
    tower_bootstrap_repeater(
        'security_checks',
        'Список перевірки',
        'checks',
        array(
            tower_bootstrap_select('security_check_icon', 'Іконка', 'icon', array('telegram' => 'Telegram', 'shield' => 'Захист', 'bank' => 'Офіс'), '25'),
            tower_bootstrap_text('security_check_label', 'Підпис', 'label', '25'),
            tower_bootstrap_text('security_check_value', 'Значення', 'value', '50'),
        ),
        'Додати пункт'
    ),
);

$office_fields = array(
    tower_bootstrap_disable('office'),
    tower_bootstrap_message('office_heading_message', 'Офіс і адреса'),
    tower_bootstrap_text('office_eyebrow', 'Надзаголовок', 'eyebrow', '30'),
    tower_bootstrap_text('office_title', 'Заголовок', 'title', '70'),
    tower_bootstrap_text('office_address_label', 'Назва локації', 'address_label', '30'),
    tower_bootstrap_text('office_address', 'Адреса', 'address', '70'),
    tower_bootstrap_textarea('office_text', 'Опис', 'text', '100'),
    tower_bootstrap_link_field('office_primary_link', 'Основна кнопка', 'primary_link', '50'),
    tower_bootstrap_link_field('office_map_link', 'Кнопка мапи', 'map_link', '50'),
    tower_bootstrap_message('office_map_message', 'Графічна мапа'),
    tower_bootstrap_text('office_map_label', 'Мітка на мапі', 'map_label', '50'),
    tower_bootstrap_text('office_coordinates', 'Координати', 'coordinates', '50'),
);

$social_fields = array(
    tower_bootstrap_disable('social'),
    tower_bootstrap_message('social_heading_message', 'Instagram'),
    tower_bootstrap_text('social_eyebrow', 'Надзаголовок', 'eyebrow', '25'),
    tower_bootstrap_text('social_title', 'Заголовок', 'title', '25'),
    tower_bootstrap_textarea('social_intro', 'Опис', 'intro', '30'),
    tower_bootstrap_link_field('social_profile_link', 'Профіль Instagram', 'profile_link', '20'),
    tower_bootstrap_message('social_posts_message', 'Публікації'),
    tower_bootstrap_repeater(
        'social_posts',
        'Картки публікацій',
        'posts',
        array(
            tower_bootstrap_text('social_post_type', 'Тип', 'type', '15'),
            tower_bootstrap_select('social_post_style', 'Стиль картки', 'style', array('yellow' => 'Жовтий', 'black' => 'Чорний', 'paper' => 'Паперовий'), '20'),
            tower_bootstrap_text('social_post_title', 'Заголовок', 'title', '35'),
            tower_bootstrap_link_field('social_post_link', 'Посилання', 'link', '30'),
        ),
        'Додати публікацію'
    ),
    tower_bootstrap_message('social_reviews_message', 'Відгуки'),
    tower_bootstrap_text('social_reviews_label', 'Підпис', 'reviews_label', '25'),
    tower_bootstrap_text('social_reviews_text', 'Текст', 'reviews_text', '35'),
    tower_bootstrap_link_field('social_reviews_link', 'Посилання', 'reviews_link', '40'),
);

$faq_fields = array(
    tower_bootstrap_disable('faq'),
    tower_bootstrap_message('faq_heading_message', 'FAQ'),
    tower_bootstrap_text('faq_eyebrow', 'Надзаголовок', 'eyebrow', '25'),
    tower_bootstrap_text('faq_title', 'Заголовок', 'title', '25'),
    tower_bootstrap_textarea('faq_intro', 'Опис', 'intro', '30'),
    tower_bootstrap_link_field('faq_contact_link', 'Контактне посилання', 'contact_link', '20'),
    tower_bootstrap_message('faq_items_message', 'Питання та відповіді'),
    tower_bootstrap_repeater(
        'faq_items',
        'FAQ',
        'items',
        array(
            tower_bootstrap_text('faq_item_question', 'Питання', 'question', '40'),
            tower_bootstrap_field('faq_item_answer', 'Відповідь', 'answer', 'wysiwyg', array('tabs' => 'visual', 'toolbar' => 'basic', 'media_upload' => 0, 'delay' => 0, 'wrapper' => array('width' => '60', 'class' => '', 'id' => ''))),
        ),
        'Додати питання'
    ),
);

$final_cta_fields = array(
    tower_bootstrap_disable('final_cta'),
    tower_bootstrap_message('final_cta_message', 'Фінальний заклик'),
    tower_bootstrap_text('final_cta_eyebrow', 'Надзаголовок', 'eyebrow', '25'),
    tower_bootstrap_text('final_cta_title', 'Заголовок', 'title', '35'),
    tower_bootstrap_textarea('final_cta_text', 'Опис', 'text', '40'),
    tower_bootstrap_link_field('final_cta_primary', 'Основна кнопка', 'primary_link', '50'),
    tower_bootstrap_link_field('final_cta_secondary', 'Друга кнопка', 'secondary_link', '50'),
);

$constructor_group = array(
    'key' => 'group_tower_constructor',
    'title' => 'Конструктор сторінки Tower Exchange',
    'fields' => array(
        tower_bootstrap_field(
            'constructor',
            'Секції сторінки',
            'constructor',
            'flexible_content',
            array(
                'layouts' => array(
                    tower_bootstrap_layout('hero', 'template-hero', 'Hero', $hero_fields),
                    tower_bootstrap_layout('calculator', 'template-calculator', 'Попередній розрахунок', $calculator_fields),
                    tower_bootstrap_layout('services', 'template-services', 'Послуги', $services_fields),
                    tower_bootstrap_layout('process', 'template-process', 'Як це працює', $process_fields),
                    tower_bootstrap_layout('security', 'template-security', 'Безпека й офіційні контакти', $security_fields),
                    tower_bootstrap_layout('office', 'template-office', 'Офіс', $office_fields),
                    tower_bootstrap_layout('social', 'template-social', 'Instagram', $social_fields),
                    tower_bootstrap_layout('faq', 'template-faq', 'FAQ', $faq_fields),
                    tower_bootstrap_layout('final_cta', 'template-final-cta', 'Фінальний CTA', $final_cta_fields),
                ),
                'button_label' => 'Додати секцію',
                'min' => 0,
                'max' => 0,
            )
        ),
    ),
    'location' => array(
        array(array('param' => 'page_template', 'operator' => '==', 'value' => 'page-constructor.php')),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array('the_content'),
    'active' => true,
    'description' => 'Редаговані секції головної сторінки.',
    'show_in_rest' => 0,
);

foreach (array($options_group, $constructor_group) as $field_group) {
    if (! acf_get_field_group($field_group['key'])) {
        acf_import_field_group($field_group);
        WP_CLI::log('Imported ACF field group: ' . $field_group['title']);
    } else {
        WP_CLI::log('Kept existing ACF field group: ' . $field_group['title']);
    }
}

$bootstrap_complete = (bool) get_option('tower_exchange_bootstrap_complete', false);

function tower_bootstrap_import_image(string $path, string $title, string $alt): int
{
    $existing = get_posts(
        array(
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'posts_per_page' => 1,
            'meta_key'       => '_tower_exchange_source',
            'meta_value'     => basename($path),
            'fields'         => 'ids',
        )
    );
    if ($existing) {
        return (int) $existing[0];
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $temporary = wp_tempnam(basename($path));
    if (! $temporary || ! copy($path, $temporary)) {
        WP_CLI::error('Unable to prepare image: ' . $path);
    }

    $attachment_id = media_handle_sideload(
        array('name' => basename($path), 'tmp_name' => $temporary),
        0,
        $title
    );
    if (is_wp_error($attachment_id)) {
        @unlink($temporary);
        WP_CLI::error($attachment_id->get_error_message());
    }

    update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt);
    update_post_meta($attachment_id, '_tower_exchange_source', basename($path));

    return (int) $attachment_id;
}

$theme_dir = get_template_directory();
$logo_light_id = tower_bootstrap_import_image($theme_dir . '/assets/brand/tower-light.png', 'Tower Exchange — світлий логотип', 'Tower Exchange');
$logo_dark_id  = tower_bootstrap_import_image($theme_dir . '/assets/brand/tower-dark.png', 'Tower Exchange — темний логотип', 'Tower Exchange');

$option_defaults = array(
    'field_tower_logo_light' => $logo_light_id,
    'field_tower_logo_dark' => $logo_dark_id,
    'field_tower_brand_name' => 'Tower Exchange',
    'field_tower_brand_subtitle' => 'Kyiv · БЦ «Парус»',
    'field_tower_header_cta' => array('url' => 'https://t.me/towerexchange_kyiv', 'title' => 'Дізнатися курс', 'target' => '_blank'),
    'field_tower_mobile_cta' => array('url' => 'https://t.me/towerexchange_kyiv', 'title' => 'Дізнатися курс', 'target' => '_blank'),
    'field_tower_footer_address_label' => 'Офіс у Києві',
    'field_tower_footer_address' => 'вул. Мечникова, 2 · БЦ «Парус»',
    'field_tower_copyright' => '© ' . wp_date('Y') . ' Tower Exchange Kyiv',
);
if (! $bootstrap_complete) {
    foreach ($option_defaults as $field_key => $value) {
        $field = get_field_object($field_key, 'option', false, false);
        $current_value = $field ? get_field($field_key, 'option') : null;
        if ($field && (false === $current_value || null === $current_value || '' === $current_value)) {
            update_field($field_key, $value, 'option');
        }
    }
}

$home_created = false;
$front_page_id = (int) get_option('page_on_front', 0);
$front_page = $front_page_id ? get_post($front_page_id) : null;
if ($front_page instanceof WP_Post && 'page' === $front_page->post_type) {
    $home_id = $front_page_id;
} else {
    $home_pages = get_posts(
        array(
            'post_type'      => 'page',
            'post_status'    => array('publish', 'draft', 'private'),
            'posts_per_page' => 1,
            'title'          => 'Головна',
        )
    );
    if ($home_pages) {
        $home_id = (int) $home_pages[0]->ID;
    } else {
        $home_id = wp_insert_post(
            array(
                'post_type'   => 'page',
                'post_title'  => 'Головна',
                'post_name'   => 'home',
                'post_status' => 'publish',
            ),
            true
        );
        if (is_wp_error($home_id)) {
            WP_CLI::error($home_id->get_error_message());
        }
        $home_id = (int) $home_id;
        $home_created = true;
    }
}
if ($home_created || ! $bootstrap_complete) {
    update_post_meta($home_id, '_wp_page_template', 'page-constructor.php');
}

$manager = 'https://t.me/towerexchange_kyiv';
$constructor_rows = array(
    array(
        'acf_fc_layout' => 'template-hero',
        'disable_block' => 0,
        'eyebrow' => 'Київ · офіс у БЦ «Парус»',
        'title' => 'USDT ↔ CASH',
        'subtitle' => 'Kyiv exchange desk',
        'lead' => 'Готівка ↔ USDT, перестановка кешу по всьому світу та безготівкові перекази. Актуальний курс і наявність уточнюйте у менеджера.',
        'primary_link' => array('url' => $manager, 'title' => 'Дізнатися курс та зробити обмін', 'target' => '_blank'),
        'secondary_link' => array('url' => '#process', 'title' => 'Як відбувається обмін', 'target' => ''),
        'location_label' => 'Адреса офісу',
        'location_text' => 'вул. Мечникова, 2 · БЦ «Парус»',
        'art_top_code' => '01',
        'art_top_text' => 'Офіційний контакт',
        'art_bottom_code' => 'UA',
        'art_bottom_text' => 'Kyiv · Parus',
        'coordinates' => '50°26′17″N',
        'trust_items' => array(
            array('label' => 'Локація', 'value' => 'Центр Києва', 'link' => ''),
            array('label' => 'Офіс', 'value' => 'БЦ «Парус»', 'link' => ''),
            array('label' => 'Менеджер', 'value' => '@towerexchange_kyiv', 'link' => ''),
            array('label' => '', 'value' => '', 'link' => array('url' => 'https://t.me/towerexchkyiv', 'title' => 'Канал з актуальним курсом', 'target' => '_blank')),
        ),
    ),
    array(
        'acf_fc_layout' => 'template-calculator',
        'disable_block' => 0,
        'eyebrow' => 'Опційний модуль · 01',
        'title' => 'Попередній розрахунок',
        'intro' => 'Вкажіть напрям і суму. Остаточний курс та наявність підтверджує менеджер Tower Exchange.',
        'accent_primary' => 'CALC / REQUEST',
        'accent_secondary' => 'KYIV 01',
        'direction_crypto_cash' => 'USDT → готівка',
        'direction_cash_crypto' => 'Готівка → USDT',
        'amount_label' => 'Віддаєте',
        'result_label' => 'Отримуєте',
        'result_text' => 'За запитом',
        'note' => 'Курс не фіксується автоматично',
        'cta_link' => array('url' => $manager, 'title' => 'Уточнити розрахунок', 'target' => '_blank'),
    ),
    array(
        'acf_fc_layout' => 'template-services',
        'disable_block' => 0,
        'eyebrow' => 'Послуги · 02',
        'title' => 'Потрібний напрям —',
        'title_accent' => 'в одному контакті',
        'items' => array(
            array('icon' => 'swap', 'title' => 'Обмін USDT', 'text' => 'Готівка → USDT та USDT → готівка. Курс і наявність підтверджує менеджер перед операцією.', 'link' => array('url' => $manager, 'title' => 'Уточнити деталі', 'target' => '_blank')),
            array('icon' => 'globe', 'title' => 'Перестановка кешу', 'text' => 'Організація видачі готівки в Україні та за кордоном. Доступність міста й умови уточнюйте у менеджера.', 'link' => array('url' => $manager, 'title' => 'Уточнити деталі', 'target' => '_blank')),
            array('icon' => 'card', 'title' => 'Безготівкові перекази', 'text' => 'Формат і доступні напрямки переказу узгоджуються індивідуально через офіційний Telegram.', 'link' => array('url' => $manager, 'title' => 'Уточнити деталі', 'target' => '_blank')),
        ),
    ),
    array(
        'acf_fc_layout' => 'template-process',
        'disable_block' => 0,
        'eyebrow' => 'Як це працює · 03',
        'title' => 'Від запиту до узгодженої операції',
        'intro' => 'Без зайвих форм: почніть діалог у Telegram і отримайте актуальні умови для вашого напрямку.',
        'cta_link' => array('url' => $manager, 'title' => 'Написати менеджеру', 'target' => '_blank'),
        'steps' => array(
            array('title' => 'Надішліть запит', 'text' => 'Напишіть у Telegram і вкажіть напрям операції, суму та потрібне місто.'),
            array('title' => 'Уточніть умови', 'text' => 'Менеджер повідомить актуальний курс, наявність і доступний формат операції.'),
            array('title' => 'Погодьте операцію', 'text' => 'Після узгодження деталей менеджер повідомить час і подальші кроки.'),
        ),
    ),
    array(
        'acf_fc_layout' => 'template-security',
        'disable_block' => 0,
        'eyebrow' => 'Офіційні контакти · 04',
        'title' => 'Перевіряйте контакт перед операцією',
        'text' => 'Використовуйте посилання на цьому сайті. Перед передаванням даних або коштів звірте нікнейм і уточніть деталі безпосередньо в офіційному чаті.',
        'cta_link' => array('url' => $manager, 'title' => 'Відкрити офіційний Telegram', 'target' => '_blank'),
        'checks' => array(
            array('icon' => 'telegram', 'label' => 'Менеджер', 'value' => '@towerexchange_kyiv'),
            array('icon' => 'shield', 'label' => 'Перед операцією', 'value' => 'Звірте нікнейм і умови'),
            array('icon' => 'bank', 'label' => 'Офіс у Києві', 'value' => 'вул. Мечникова, 2'),
        ),
    ),
    array(
        'acf_fc_layout' => 'template-office',
        'disable_block' => 0,
        'eyebrow' => 'Офіс · 05',
        'title' => 'Tower Exchange у центрі Києва',
        'address_label' => 'БЦ «Парус»',
        'address' => 'вул. Мечникова, 2, Київ',
        'text' => 'Перед візитом напишіть менеджеру, щоб уточнити актуальний курс, наявність і деталі операції.',
        'primary_link' => array('url' => $manager, 'title' => 'Зв’язатися перед візитом', 'target' => '_blank'),
        'map_link' => array('url' => 'https://www.google.com/maps/search/?api=1&query=вул.+Мечникова+2+БЦ+Парус+Київ', 'title' => 'Відкрити на мапі', 'target' => '_blank'),
        'map_label' => 'Tower Exchange',
        'coordinates' => '50.4382° N · 30.5231° E',
    ),
    array(
        'acf_fc_layout' => 'template-social',
        'disable_block' => 0,
        'eyebrow' => 'Instagram · 06',
        'title' => 'Tower у стрічці',
        'intro' => 'Новини, процес обміну та життя Tower Exchange у Києві.',
        'profile_link' => array('url' => 'https://www.instagram.com/tower.exchange.kyiv/', 'title' => '@tower.exchange.kyiv', 'target' => '_blank'),
        'posts' => array(
            array('type' => 'REEL', 'style' => 'yellow', 'title' => 'Працюємо для вашого комфорту', 'link' => array('url' => 'https://www.instagram.com/tower.exchange.kyiv/reel/DNf4wPVquxE/', 'title' => 'Відкрити', 'target' => '_blank')),
            array('type' => 'REEL', 'style' => 'black', 'title' => 'На сторожі вашого гаманця', 'link' => array('url' => 'https://www.instagram.com/tower.exchange.kyiv/reel/DSfZCbtCjDp/', 'title' => 'Відкрити', 'target' => '_blank')),
            array('type' => 'REEL', 'style' => 'paper', 'title' => 'Фінансові послуги по всьому світу', 'link' => array('url' => 'https://www.instagram.com/tower.exchange.kyiv/reel/DMK43ErNyfQ/', 'title' => 'Відкрити', 'target' => '_blank')),
        ),
        'reviews_label' => 'Відгуки клієнтів',
        'reviews_text' => 'Переглядайте в актуальному Instagram',
        'reviews_link' => array('url' => 'https://www.instagram.com/stories/highlights/17900850174112330/', 'title' => 'Переглянути відгуки', 'target' => '_blank'),
    ),
    array(
        'acf_fc_layout' => 'template-faq',
        'disable_block' => 0,
        'eyebrow' => 'FAQ · 07',
        'title' => 'Коротко про головне',
        'intro' => 'Не знайшли відповіді? Поставте питання менеджеру в Telegram.',
        'contact_link' => array('url' => $manager, 'title' => 'Зв’язатися з менеджером', 'target' => '_blank'),
        'items' => array(
            array('question' => 'Які напрями обміну доступні?', 'answer' => '<p>Основний напрям — готівка → USDT та USDT → готівка. Інші формати уточнюйте у менеджера.</p>'),
            array('question' => 'Як дізнатися актуальний курс?', 'answer' => '<p>Напишіть @towerexchange_kyiv у Telegram або перейдіть до каналу @towerexchkyiv з актуальними курсами.</p>'),
            array('question' => 'Чи потрібно уточнювати наявність?', 'answer' => '<p>Так. Актуальний курс і наявність потрібно підтвердити у менеджера перед операцією.</p>'),
            array('question' => 'Де розташований офіс?', 'answer' => '<p>У Києві за адресою: вул. Мечникова, 2, БЦ «Парус».</p>'),
            array('question' => 'Чи доступні послуги за межами Києва?', 'answer' => '<p>Tower Exchange пропонує перестановку кешу по світу та безготівкові перекази. Доступність конкретного міста чи країни уточнюйте у менеджера.</p>'),
            array('question' => 'Як перевірити контакт менеджера?', 'answer' => '<p>Використовуйте посилання на @towerexchange_kyiv з цього сайту, офіційного Linktree або Telegram-каналу Tower Exchange Kyiv.</p>'),
        ),
    ),
    array(
        'acf_fc_layout' => 'template-final-cta',
        'disable_block' => 0,
        'eyebrow' => 'Tower Exchange Kyiv',
        'title' => 'Уточніть курс і формат операції',
        'text' => 'Напишіть менеджеру, щоб підтвердити актуальний курс, наявність і подальші кроки.',
        'primary_link' => array('url' => $manager, 'title' => 'Написати в Telegram', 'target' => '_blank'),
        'secondary_link' => array('url' => 'https://t.me/towerexchkyiv', 'title' => 'Дивитися актуальний курс', 'target' => '_blank'),
    ),
);

$current_constructor = get_field('constructor', $home_id, false);
if (($home_created || ! $bootstrap_complete) && empty($current_constructor)) {
    update_field('field_tower_constructor', $constructor_rows, $home_id);
    WP_CLI::log('Populated the home page constructor.');
} else {
    WP_CLI::log('Kept the existing home page constructor content.');
}

function tower_bootstrap_menu(string $name, array $items): int
{
    $menu = wp_get_nav_menu_object($name);
    $menu_result = $menu ? $menu->term_id : wp_create_nav_menu($name);
    if (is_wp_error($menu_result)) {
        WP_CLI::error($menu_result->get_error_message());
    }
    $menu_id = (int) $menu_result;

    if (! wp_get_nav_menu_items($menu_id)) {
        foreach ($items as $item) {
            $menu_item_id = wp_update_nav_menu_item(
                $menu_id,
                0,
                array(
                    'menu-item-title'  => $item['title'],
                    'menu-item-url'    => $item['url'],
                    'menu-item-target' => $item['target'] ?? '',
                    'menu-item-xfn'    => '_blank' === ($item['target'] ?? '') ? 'noopener noreferrer' : '',
                    'menu-item-status' => 'publish',
                    'menu-item-type'   => 'custom',
                )
            );
            if (is_wp_error($menu_item_id)) {
                WP_CLI::error($menu_item_id->get_error_message());
            }
        }
    }

    return $menu_id;
}

if (! $bootstrap_complete) {
    $header_items = array(
        array('title' => 'Послуги', 'url' => home_url('/#services')),
        array('title' => 'Як це працює', 'url' => home_url('/#process')),
        array('title' => 'Безпека', 'url' => home_url('/#security')),
        array('title' => 'Instagram', 'url' => home_url('/#instagram')),
        array('title' => 'Контакти', 'url' => home_url('/#contacts')),
    );
    $desktop_menu_id = tower_bootstrap_menu('Tower Header Desktop', $header_items);
    $mobile_menu_id  = tower_bootstrap_menu('Tower Header Mobile', $header_items);
    $social_menu_id  = tower_bootstrap_menu(
        'Tower Footer Social',
        array(
            array('title' => 'Telegram', 'url' => $manager, 'target' => '_blank'),
            array('title' => 'Instagram', 'url' => 'https://www.instagram.com/tower.exchange.kyiv/', 'target' => '_blank'),
        )
    );
    $locations = get_theme_mod('nav_menu_locations', array());
    $locations['header-desktop'] = $desktop_menu_id;
    $locations['header-mobile'] = $mobile_menu_id;
    $locations['footer-social'] = $social_menu_id;
    set_theme_mod('nav_menu_locations', $locations);

    update_option('blogname', 'Tower Exchange Kyiv');
    update_option('blogdescription', 'Обмін USDT у центрі Києва');
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);
    update_option('page_for_posts', 0);
    update_option('permalink_structure', '/%category%/%postname%/');
    update_option('timezone_string', 'Europe/Kyiv');
    flush_rewrite_rules(true);
    update_option('tower_exchange_bootstrap_complete', '1', false);
}

WP_CLI::success('Tower Exchange WordPress integration bootstrap completed. Home page ID: ' . $home_id);
