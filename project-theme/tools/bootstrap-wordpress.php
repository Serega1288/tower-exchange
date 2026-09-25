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
            'message' => '',
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

$coverage_fields = array(
    tower_bootstrap_disable('coverage'),
    tower_bootstrap_message('coverage_heading_message', 'Заголовок секції'),
    tower_bootstrap_text('coverage_eyebrow', 'Надзаголовок', 'eyebrow', '25'),
    tower_bootstrap_text('coverage_title', 'Заголовок', 'title', '35'),
    tower_bootstrap_textarea('coverage_intro', 'Вступ', 'intro', '40'),
    tower_bootstrap_message('coverage_directions_message', 'Напрями обміну'),
    tower_bootstrap_text('coverage_direction_cash_crypto', 'Напрям 1', 'direction_cash_crypto', '50'),
    tower_bootstrap_text('coverage_direction_crypto_cash', 'Напрям 2', 'direction_crypto_cash', '50'),
    tower_bootstrap_message('coverage_countries_message', 'Країни та міста'),
    tower_bootstrap_text('coverage_countries_title', 'Заголовок списку', 'countries_title', '100'),
    tower_bootstrap_repeater(
        'coverage_countries',
        'Список країн і міст',
        'countries',
        array(
            tower_bootstrap_text('coverage_country_code', 'Код країни', 'code', '15'),
            tower_bootstrap_text('coverage_country_name', 'Країна', 'country', '25'),
            tower_bootstrap_text('coverage_country_cities', 'Міста', 'cities', '60'),
        ),
        'Додати країну'
    ),
    tower_bootstrap_text('coverage_other_cities', 'Примітка про інші міста', 'other_cities', '100'),
    tower_bootstrap_message('coverage_payments_message', 'Перекази та оплати'),
    tower_bootstrap_text('coverage_payments_title', 'Заголовок списку', 'payments_title', '100'),
    tower_bootstrap_repeater(
        'coverage_payments',
        'Способи переказів та оплат',
        'payments',
        array(
            tower_bootstrap_text('coverage_payment_label', 'Категорія', 'label', '30'),
            tower_bootstrap_text('coverage_payment_details', 'Сервіси або опис', 'details', '70'),
        ),
        'Додати спосіб'
    ),
    tower_bootstrap_text('coverage_same_day_text', 'Акцентний текст', 'same_day_text', '50'),
    tower_bootstrap_link_field('coverage_cta_link', 'Кнопка Telegram', 'cta_link', '50'),
);
$coverage_layout = tower_bootstrap_layout(
    'coverage',
    'template-coverage',
    'Географія та перекази',
    $coverage_fields
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

$office_map_embed_field = tower_bootstrap_field(
    'office_map_embed_url',
    'Google Maps — URL для вбудовування',
    'map_embed_url',
    'url',
    array(
        'instructions' => 'У Google Maps оберіть «Поділитися» → «Вбудувати карту» і вставте лише URL з атрибута src.',
        'wrapper' => array('width' => '100', 'class' => '', 'id' => ''),
    )
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
    tower_bootstrap_message('office_map_message', 'Google Map'),
    tower_bootstrap_text('office_map_label', 'Мітка на мапі', 'map_label', '50'),
    tower_bootstrap_text('office_coordinates', 'Координати', 'coordinates', '50'),
    $office_map_embed_field,
);

$social_post_cover_field = tower_bootstrap_field(
    'social_post_cover',
    'Обкладинка',
    'cover',
    'image',
    array(
        'instructions'  => 'Вертикальна обкладинка публікації або Reels. Рекомендоване співвідношення сторін — 9:16.',
        'return_format' => 'id',
        'preview_size'  => 'medium',
        'library'       => 'all',
        'wrapper'       => array('width' => '100', 'class' => '', 'id' => ''),
    )
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
            $social_post_cover_field,
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

$simple_text_fields = array(
    tower_bootstrap_disable('simple_text'),
    tower_bootstrap_message('simple_text_heading_message', 'Текстовий блок'),
    tower_bootstrap_text('simple_text_title', 'Заголовок', 'title', '100'),
    tower_bootstrap_field(
        'simple_text_content',
        'Текст',
        'content',
        'wysiwyg',
        array(
            'tabs'         => 'visual',
            'toolbar'      => 'basic',
            'media_upload' => 0,
            'delay'        => 0,
            'wrapper'      => array('width' => '100', 'class' => '', 'id' => ''),
        )
    ),
);
$simple_text_layout = tower_bootstrap_layout(
    'simple_text',
    'template-simple-text',
    'Текстовий блок',
    $simple_text_fields
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
                    $simple_text_layout,
                    tower_bootstrap_layout('hero', 'template-hero', 'Hero', $hero_fields),
                    tower_bootstrap_layout('calculator', 'template-calculator', 'Попередній розрахунок', $calculator_fields),
                    tower_bootstrap_layout('services', 'template-services', 'Послуги', $services_fields),
                    $coverage_layout,
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

/**
 * Return every message field from a field definition, including nested layouts.
 *
 * @param array<int, array<string, mixed>> $fields ACF field definitions.
 * @return array<int, array<string, mixed>>
 */
function tower_bootstrap_collect_message_fields(array $fields): array
{
    $messages = array();

    foreach ($fields as $field) {
        if ('message' === ($field['type'] ?? '')) {
            $messages[] = $field;
        }

        if (! empty($field['sub_fields']) && is_array($field['sub_fields'])) {
            $messages = array_merge($messages, tower_bootstrap_collect_message_fields($field['sub_fields']));
        }

        if (! empty($field['layouts']) && is_array($field['layouts'])) {
            foreach ($field['layouts'] as $layout) {
                if (! empty($layout['sub_fields']) && is_array($layout['sub_fields'])) {
                    $messages = array_merge($messages, tower_bootstrap_collect_message_fields($layout['sub_fields']));
                }
            }
        }
    }

    return $messages;
}

/**
 * Remove the duplicated body from message fields created by the first bootstrap.
 *
 * Only fields whose message still matches their label are changed, so any message
 * customized later in ACF remains untouched.
 *
 * @param array<int, array<string, mixed>> $field_groups ACF field groups.
 */
function tower_bootstrap_migrate_duplicate_messages(array $field_groups): void
{
    $migration_version = '1';
    if ($migration_version === get_option('tower_exchange_acf_message_cleanup_version')) {
        return;
    }

    $updated = 0;
    foreach ($field_groups as $field_group) {
        $message_fields = tower_bootstrap_collect_message_fields($field_group['fields'] ?? array());

        foreach ($message_fields as $message_field) {
            $stored_field = acf_get_field($message_field['key']);
            if (! $stored_field) {
                continue;
            }

            $stored_label   = trim(wp_strip_all_tags((string) ($stored_field['label'] ?? '')));
            $stored_message = trim(wp_strip_all_tags((string) ($stored_field['message'] ?? '')));
            if ('' === $stored_message || $stored_message !== $stored_label) {
                continue;
            }

            $stored_field['message'] = '';
            acf_update_field($stored_field);
            ++$updated;
        }
    }

    update_option('tower_exchange_acf_message_cleanup_version', $migration_version, false);
    WP_CLI::log(sprintf('Removed duplicated text from %d ACF section headings.', $updated));
}

/**
 * Add the Google Maps embed field to an already imported constructor group.
 *
 * Existing ACF groups are intentionally not re-imported, so this migration
 * adds only the new field and preserves any editor changes to the group.
 */
function tower_bootstrap_migrate_office_map_embed_field(array $field): void
{
    $migration_version = '1';
    if ($migration_version === get_option('tower_exchange_acf_map_embed_field_version')) {
        return;
    }

    if (! acf_get_field($field['key'])) {
        $field['parent'] = 'field_tower_constructor';
        $field['parent_layout'] = 'layout_tower_office';
        $field['menu_order'] = 12;
        $updated_field = acf_update_field($field);

        if (empty($updated_field['ID'])) {
            WP_CLI::warning('Could not add the Google Maps embed field. The migration will retry next time.');
            return;
        }

        WP_CLI::log('Added the Google Maps embed field to the Office layout.');
    }

    $map_message = acf_get_field('field_tower_office_map_message');
    if ($map_message && 'Графічна мапа' === ($map_message['label'] ?? '')) {
        $map_message['label'] = 'Google Map';
        acf_update_field($map_message);
    }

    update_option('tower_exchange_acf_map_embed_field_version', $migration_version, false);
}

/**
 * Add the Reel cover field to the existing Social repeater.
 *
 * Existing ACF groups are intentionally not re-imported, so the field is
 * attached directly to the stored repeater and editor customizations remain.
 *
 * @param array<string, mixed> $field ACF image field definition.
 */
function tower_bootstrap_migrate_social_post_cover_field(array $field): void
{
    $migration_version = '1';
    if ($migration_version === get_option('tower_exchange_acf_social_cover_field_version')) {
        return;
    }

    $posts_field = acf_get_field('field_tower_social_posts');
    if (! $posts_field) {
        WP_CLI::warning('Could not find the Social posts repeater. The cover field migration will retry next time.');
        return;
    }

    if (! acf_get_field($field['key'])) {
        $field['parent']     = ! empty($posts_field['ID']) ? (int) $posts_field['ID'] : $posts_field['key'];
        $field['menu_order'] = count($posts_field['sub_fields'] ?? array());
        $updated_field       = acf_update_field($field);

        if (empty($updated_field['ID'])) {
            WP_CLI::warning('Could not add the Social post cover field. The migration will retry next time.');
            return;
        }

        WP_CLI::log('Added the cover field to Social post cards.');

        // The constructor may already be cached with the repeater's previous
        // sub-field list. Reload ACF fields so covers can be seeded in this run.
        $fields_store = acf_get_store('fields');
        if ($fields_store) {
            $fields_store->reset();
        }
        $posts_field = acf_get_field('field_tower_social_posts');
        if (! $posts_field) {
            WP_CLI::warning('Could not reload the Social posts repeater. The cover field migration will retry next time.');
            return;
        }
    }

    $stored_field  = acf_get_field($field['key']);
    $valid_parents = array_filter(
        array(
            (string) ($posts_field['ID'] ?? ''),
            (string) ($posts_field['key'] ?? ''),
        )
    );
    if (
        ! $stored_field
        || ! in_array((string) ($stored_field['parent'] ?? ''), $valid_parents, true)
        || 'image' !== ($stored_field['type'] ?? '')
        || 'cover' !== ($stored_field['name'] ?? '')
        || 'id' !== ($stored_field['return_format'] ?? '')
    ) {
        WP_CLI::warning('Could not verify the Social post cover field. The migration will retry next time.');
        return;
    }

    update_option('tower_exchange_acf_social_cover_field_version', $migration_version, false);
}

/**
 * Add the reusable text layout without re-importing the editor-managed group.
 *
 * @param array<string, mixed> $layout Flexible Content layout definition.
 */
function tower_bootstrap_migrate_simple_text_layout(array $layout): void
{
    $migration_version = '1';
    if ($migration_version === get_option('tower_exchange_acf_simple_text_layout_version')) {
        return;
    }

    $constructor_field = acf_get_field('field_tower_constructor');
    if (! $constructor_field) {
        WP_CLI::warning('Could not find the constructor field. The text layout migration will retry next time.');
        return;
    }

    $layout_key = '';
    foreach (($constructor_field['layouts'] ?? array()) as $stored_layout) {
        if (
            ($stored_layout['key'] ?? '') === ($layout['key'] ?? '')
            || ($stored_layout['name'] ?? '') === ($layout['name'] ?? '')
        ) {
            $layout_key = (string) ($stored_layout['key'] ?? $layout['key']);
            break;
        }
    }

    if ('' === $layout_key) {
        $layout_metadata = $layout;
        unset($layout_metadata['sub_fields']);
        $constructor_field['layouts'][] = $layout_metadata;
        acf_update_field($constructor_field);
        $layout_key = (string) $layout['key'];
        WP_CLI::log('Added the reusable text layout to the page constructor.');
    }

    foreach (($layout['sub_fields'] ?? array()) as $menu_order => $field) {
        if (acf_get_field($field['key'])) {
            continue;
        }

        $field['parent']        = 'field_tower_constructor';
        $field['parent_layout'] = $layout_key;
        $field['menu_order']    = $menu_order;
        acf_update_field($field);
    }

    $verified_constructor = acf_get_field('field_tower_constructor');
    $verified_layout      = false;
    foreach (($verified_constructor['layouts'] ?? array()) as $stored_layout) {
        if (($stored_layout['key'] ?? '') === $layout_key) {
            $verified_layout = true;
            break;
        }
    }

    $verified_fields = true;
    foreach (($layout['sub_fields'] ?? array()) as $field) {
        $stored_field = acf_get_field($field['key']);
        if (! $stored_field || $layout_key !== ($stored_field['parent_layout'] ?? '')) {
            $verified_fields = false;
            break;
        }
    }

    if (! $verified_layout || ! $verified_fields) {
        WP_CLI::warning('Could not verify the reusable text layout. The migration will retry next time.');
        return;
    }

    update_option('tower_exchange_acf_simple_text_layout_version', $migration_version, false);
}

/**
 * Add the Coverage layout and its repeater children without replacing the
 * editor-managed constructor group.
 *
 * @param array<string, mixed> $layout Flexible Content layout definition.
 */
function tower_bootstrap_migrate_coverage_layout(array $layout): void
{
    $migration_version = '1';
    if ($migration_version === get_option('tower_exchange_acf_coverage_layout_version')) {
        return;
    }

    $constructor_field = acf_get_field('field_tower_constructor');
    if (! $constructor_field) {
        WP_CLI::warning('Could not find the constructor field. The Coverage layout migration will retry next time.');
        return;
    }

    $layout_key = '';
    foreach (($constructor_field['layouts'] ?? array()) as $stored_layout) {
        if (
            ($stored_layout['key'] ?? '') === ($layout['key'] ?? '')
            || ($stored_layout['name'] ?? '') === ($layout['name'] ?? '')
        ) {
            $layout_key = (string) ($stored_layout['key'] ?? $layout['key']);
            break;
        }
    }

    if ('' === $layout_key) {
        $layout_metadata = $layout;
        unset($layout_metadata['sub_fields']);

        $layouts       = array_values($constructor_field['layouts'] ?? array());
        $insert_offset = count($layouts);
        foreach ($layouts as $index => $stored_layout) {
            if ('template-services' === ($stored_layout['name'] ?? '')) {
                $insert_offset = $index + 1;
                break;
            }
        }
        array_splice($layouts, $insert_offset, 0, array($layout_metadata));
        $constructor_field['layouts'] = $layouts;
        acf_update_field($constructor_field);
        $layout_key = (string) $layout['key'];
        WP_CLI::log('Added the Coverage layout to the page constructor.');

        $fields_store = acf_get_store('fields');
        if ($fields_store) {
            $fields_store->reset();
        }
    }

    foreach (($layout['sub_fields'] ?? array()) as $menu_order => $field_definition) {
        $children = $field_definition['sub_fields'] ?? array();
        unset($field_definition['sub_fields']);

        $stored_field = acf_get_field($field_definition['key']);
        if (! $stored_field) {
            $field_definition['parent']        = 'field_tower_constructor';
            $field_definition['parent_layout'] = $layout_key;
            $field_definition['menu_order']    = $menu_order;
            $stored_field                      = acf_update_field($field_definition);
        }

        if (! $stored_field || $layout_key !== ($stored_field['parent_layout'] ?? '')) {
            WP_CLI::warning('Could not add a Coverage field. The migration will retry next time.');
            return;
        }

        if (! $children) {
            continue;
        }

        $child_parent = ! empty($stored_field['ID']) ? (int) $stored_field['ID'] : $stored_field['key'];
        foreach ($children as $child_order => $child_definition) {
            if (acf_get_field($child_definition['key'])) {
                continue;
            }

            $child_definition['parent']     = $child_parent;
            $child_definition['menu_order'] = $child_order;
            acf_update_field($child_definition);
        }
    }

    $fields_store = acf_get_store('fields');
    if ($fields_store) {
        $fields_store->reset();
    }

    $verified_constructor = acf_get_field('field_tower_constructor');
    $verified_layout      = false;
    foreach (($verified_constructor['layouts'] ?? array()) as $stored_layout) {
        if (($stored_layout['key'] ?? '') === $layout_key) {
            $verified_layout = true;
            break;
        }
    }

    if (! $verified_layout) {
        WP_CLI::warning('Could not verify the Coverage layout. The migration will retry next time.');
        return;
    }

    foreach (($layout['sub_fields'] ?? array()) as $field_definition) {
        $stored_field = acf_get_field($field_definition['key']);
        if (! $stored_field || $layout_key !== ($stored_field['parent_layout'] ?? '')) {
            WP_CLI::warning('Could not verify the Coverage fields. The migration will retry next time.');
            return;
        }

        $valid_child_parents = array_filter(
            array(
                (string) ($stored_field['ID'] ?? ''),
                (string) ($stored_field['key'] ?? ''),
            )
        );
        foreach (($field_definition['sub_fields'] ?? array()) as $child_definition) {
            $stored_child = acf_get_field($child_definition['key']);
            if (! $stored_child || ! in_array((string) ($stored_child['parent'] ?? ''), $valid_child_parents, true)) {
                WP_CLI::warning('Could not verify the Coverage repeater fields. The migration will retry next time.');
                return;
            }
        }
    }

    update_option('tower_exchange_acf_coverage_layout_version', $migration_version, false);
}

foreach (array($options_group, $constructor_group) as $field_group) {
    if (! acf_get_field_group($field_group['key'])) {
        acf_import_field_group($field_group);
        WP_CLI::log('Imported ACF field group: ' . $field_group['title']);
    } else {
        WP_CLI::log('Kept existing ACF field group: ' . $field_group['title']);
    }
}

tower_bootstrap_migrate_duplicate_messages(array($options_group, $constructor_group));
tower_bootstrap_migrate_office_map_embed_field($office_map_embed_field);
tower_bootstrap_migrate_social_post_cover_field($social_post_cover_field);
tower_bootstrap_migrate_simple_text_layout($simple_text_layout);
tower_bootstrap_migrate_coverage_layout($coverage_layout);

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
$social_cover_ids = array(
    'DNf4wPVquxE' => tower_bootstrap_import_image(
        $theme_dir . '/assets/social/reel-comfort.jpg',
        'Instagram — Працюємо для вашого комфорту',
        'Працюємо для вашого комфорту — Tower Exchange'
    ),
    'DSfZCbtCjDp' => tower_bootstrap_import_image(
        $theme_dir . '/assets/social/reel-wallet.jpg',
        'Instagram — На сторожі вашого кохання',
        'На сторожі вашого кохання — Tower Exchange'
    ),
    'DMK43ErNyfQ' => tower_bootstrap_import_image(
        $theme_dir . '/assets/social/reel-worldwide.jpg',
        'Instagram — Сервіси, що знаються в якості',
        'Сервіси, що знаються в якості — Tower Exchange'
    ),
);

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
$google_maps_embed_url = 'https://www.google.com/maps/embed?origin=mfe&pb=!1m2!2m1!1z0JHQpiDQn9Cw0YDRg9GBLCDQstGD0LsuINCc0LXRh9C90LjQutC-0LLQsCwgMiwg0JrQuNGX0LI';
$coverage_countries = array(
    array('code' => 'UA', 'country' => 'Україна', 'cities' => 'Київ, Одеса, Львів, Дніпро, Харків'),
    array('code' => 'PL', 'country' => 'Польща', 'cities' => 'Варшава, Краків, Познань, Вроцлав'),
    array('code' => 'DE', 'country' => 'Німеччина', 'cities' => 'Берлін, Дортмунд, Мюнхен, Франкфурт, Дюссельдорф'),
    array('code' => 'AT', 'country' => 'Австрія', 'cities' => 'Відень'),
    array('code' => 'ES', 'country' => 'Іспанія', 'cities' => 'Барселона, Мадрид, Аліканте, Валенсія, Марбелья'),
    array('code' => 'CZ', 'country' => 'Чехія', 'cities' => 'Прага'),
    array('code' => 'IT', 'country' => 'Італія', 'cities' => 'Мілан, Рим'),
    array('code' => 'CH', 'country' => 'Швейцарія', 'cities' => 'Цюрих, Женева, Люцерн'),
    array('code' => 'GR', 'country' => 'Греція', 'cities' => 'Афіни, Салоніки'),
    array('code' => 'SK', 'country' => 'Словаччина', 'cities' => 'Братислава'),
    array('code' => 'ME', 'country' => 'Чорногорія', 'cities' => 'Будва, Тиват'),
    array('code' => 'RO', 'country' => 'Румунія', 'cities' => 'Бухарест'),
    array('code' => 'BG', 'country' => 'Болгарія', 'cities' => 'Софія, Варна'),
    array('code' => 'GB', 'country' => 'Англія', 'cities' => 'Лондон'),
    array('code' => 'PT', 'country' => 'Португалія', 'cities' => 'Лісабон, Кашкайш'),
    array('code' => 'TR', 'country' => 'Туреччина', 'cities' => 'Стамбул, Анталія, Аланія, Мерсін'),
    array('code' => 'US', 'country' => 'США', 'cities' => 'Нью-Йорк, Маямі, Лос-Анджелес'),
    array('code' => 'CA', 'country' => 'Канада', 'cities' => 'Торонто'),
    array('code' => 'AE', 'country' => 'ОАЕ', 'cities' => 'Дубай'),
    array('code' => 'NL', 'country' => 'Нідерланди', 'cities' => 'Амстердам, Роттердам'),
    array('code' => 'BE', 'country' => 'Бельгія', 'cities' => 'Брюссель'),
    array('code' => 'CY', 'country' => 'Кіпр', 'cities' => 'Лімасол'),
    array('code' => 'IL', 'country' => 'Ізраїль', 'cities' => 'Тель-Авів та ін. міста'),
);
$coverage_payments = array(
    array('label' => 'USD / EUR', 'details' => 'SWIFT, SEPA, Wise, Revolut, Paysera'),
    array('label' => 'CNY', 'details' => 'Alipay, WeChat'),
    array('label' => 'Оплата', 'details' => 'PayPal, Copart'),
    array('label' => 'UAH', 'details' => 'Перекази гривні на картку'),
);
$coverage_row = array(
    'acf_fc_layout'                              => 'template-coverage',
    'field_tower_coverage_disable'               => 0,
    'field_tower_coverage_eyebrow'               => 'Географія · World Desk',
    'field_tower_coverage_title'                 => 'Працюємо по всьому світу',
    'field_tower_coverage_intro'                 => 'Оберіть країну й потрібне місто. Доступність і деталі операції підтверджує менеджер.',
    'field_tower_coverage_direction_cash_crypto' => 'Готівка → USDT',
    'field_tower_coverage_direction_crypto_cash' => 'USDT → готівка',
    'field_tower_coverage_countries_title'       => 'Країни та міста',
    'field_tower_coverage_countries'             => array_map(
        static fn(array $country): array => array(
            'field_tower_coverage_country_code'   => $country['code'],
            'field_tower_coverage_country_name'   => $country['country'],
            'field_tower_coverage_country_cities' => $country['cities'],
        ),
        $coverage_countries
    ),
    'field_tower_coverage_other_cities'          => 'Інші міста — за запитом!',
    'field_tower_coverage_payments_title'        => 'Перекази та оплати',
    'field_tower_coverage_payments'              => array_map(
        static fn(array $payment): array => array(
            'field_tower_coverage_payment_label'   => $payment['label'],
            'field_tower_coverage_payment_details' => $payment['details'],
        ),
        $coverage_payments
    ),
    'field_tower_coverage_same_day_text'         => 'Видача в той самий день!',
    'field_tower_coverage_cta_link'              => array('url' => $manager, 'title' => 'Уточнити місто та умови', 'target' => '_blank'),
);
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
    $coverage_row,
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
        'map_embed_url' => $google_maps_embed_url,
    ),
    array(
        'acf_fc_layout' => 'template-social',
        'disable_block' => 0,
        'eyebrow' => 'Instagram · 06',
        'title' => 'Tower у стрічці',
        'intro' => 'Новини, процес обміну та життя Tower Exchange у Києві.',
        'profile_link' => array('url' => 'https://www.instagram.com/tower.exchange.kyiv/', 'title' => '@tower.exchange.kyiv', 'target' => '_blank'),
        'posts' => array(
            array('type' => 'REEL', 'style' => 'yellow', 'title' => 'Працюємо для вашого комфорту', 'link' => array('url' => 'https://www.instagram.com/tower.exchange.kyiv/reel/DNf4wPVquxE/', 'title' => 'Відкрити', 'target' => '_blank'), 'cover' => $social_cover_ids['DNf4wPVquxE']),
            array('type' => 'REEL', 'style' => 'black', 'title' => 'На сторожі вашого кохання', 'link' => array('url' => 'https://www.instagram.com/tower.exchange.kyiv/reel/DSfZCbtCjDp/', 'title' => 'Відкрити', 'target' => '_blank'), 'cover' => $social_cover_ids['DSfZCbtCjDp']),
            array('type' => 'REEL', 'style' => 'paper', 'title' => 'Сервіси, що знаються в якості', 'link' => array('url' => 'https://www.instagram.com/tower.exchange.kyiv/reel/DMK43ErNyfQ/', 'title' => 'Відкрити', 'target' => '_blank'), 'cover' => $social_cover_ids['DMK43ErNyfQ']),
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

/**
 * Insert the Coverage section once into an existing non-empty constructor.
 * Existing Coverage rows and every other editor-managed row are preserved.
 *
 * @param array<string, mixed> $coverage_row Raw ACF row keyed by field keys.
 */
function tower_bootstrap_migrate_coverage_row(int $page_id, array $coverage_row): void
{
    $migration_version = '1';
    if ($migration_version === get_option('tower_exchange_coverage_row_version')) {
        return;
    }

    $rows = get_field('constructor', $page_id, false);
    if (! is_array($rows)) {
        WP_CLI::warning('Could not read the page constructor. The Coverage row migration will retry next time.');
        return;
    }

    foreach ($rows as $row) {
        if ('template-coverage' === ($row['acf_fc_layout'] ?? '')) {
            update_option('tower_exchange_coverage_row_version', $migration_version, false);
            WP_CLI::log('Kept the existing Coverage section content.');
            return;
        }
    }

    $insert_offset = count($rows);
    foreach ($rows as $index => $row) {
        if ('template-services' === ($row['acf_fc_layout'] ?? '')) {
            $insert_offset = $index + 1;
            break;
        }
        if ('template-process' === ($row['acf_fc_layout'] ?? '') && count($rows) === $insert_offset) {
            $insert_offset = $index;
        }
    }

    array_splice($rows, $insert_offset, 0, array($coverage_row));
    update_field('field_tower_constructor', $rows, $page_id);

    $verified_rows = get_field('constructor', $page_id, false);
    $verified      = 0;
    foreach (is_array($verified_rows) ? $verified_rows : array() as $verified_row) {
        if ('template-coverage' === ($verified_row['acf_fc_layout'] ?? '')) {
            ++$verified;
        }
    }

    if (1 !== $verified) {
        WP_CLI::warning('Could not verify the Coverage section. The migration will retry next time.');
        return;
    }

    update_option('tower_exchange_coverage_row_version', $migration_version, false);
    WP_CLI::log('Inserted the Coverage section after Services.');
}

tower_bootstrap_migrate_coverage_row($home_id, $coverage_row);

/**
 * Populate only an empty embed URL in existing Office rows.
 */
function tower_bootstrap_migrate_office_map_embed_value(int $page_id, string $embed_url): void
{
    $migration_version = '1';
    if ($migration_version === get_option('tower_exchange_acf_map_embed_value_version')) {
        return;
    }

    $rows = get_field('constructor', $page_id, false);
    if (! is_array($rows)) {
        return;
    }

    $updated = 0;
    foreach ($rows as $index => $row) {
        if ('template-office' !== ($row['acf_fc_layout'] ?? '')) {
            continue;
        }

        $current_url = $row['field_tower_office_map_embed_url'] ?? $row['map_embed_url'] ?? '';
        if (is_string($current_url) && '' !== trim($current_url)) {
            continue;
        }

        $rows[$index]['field_tower_office_map_embed_url'] = $embed_url;
        ++$updated;
    }

    if ($updated) {
        update_field('field_tower_constructor', $rows, $page_id);

        $verified_rows = get_field('constructor', $page_id, false);
        foreach (is_array($verified_rows) ? $verified_rows : array() as $verified_row) {
            if ('template-office' !== ($verified_row['acf_fc_layout'] ?? '')) {
                continue;
            }

            $verified_url = $verified_row['field_tower_office_map_embed_url'] ?? $verified_row['map_embed_url'] ?? '';
            if (! is_string($verified_url) || '' === trim($verified_url)) {
                WP_CLI::warning('Could not populate the Google Maps embed URL. The migration will retry next time.');
                return;
            }
        }
    }

    update_option('tower_exchange_acf_map_embed_value_version', $migration_version, false);
    WP_CLI::log(sprintf('Populated the Google Maps embed URL in %d Office section(s).', $updated));
}

tower_bootstrap_migrate_office_map_embed_value($home_id, $google_maps_embed_url);

/**
 * Populate local Reel covers only for known cards whose cover is still empty.
 *
 * The migration matches by the public Reel URL, so reordered cards and custom
 * cards are preserved. Seed titles are corrected only while they still match
 * the original bootstrap copy.
 *
 * @param array<string, int> $cover_ids Attachment IDs keyed by Reel shortcode.
 */
function tower_bootstrap_migrate_social_post_covers(int $page_id, array $cover_ids): void
{
    $migration_version = '1';
    if ($migration_version === get_option('tower_exchange_social_covers_version')) {
        return;
    }

    $rows = get_field('constructor', $page_id, false);
    if (! is_array($rows)) {
        WP_CLI::warning('Could not read the page constructor. The Social cover migration will retry next time.');
        return;
    }

    foreach ($cover_ids as $shortcode => $attachment_id) {
        if (! $attachment_id || ! wp_attachment_is_image((int) $attachment_id)) {
            WP_CLI::warning('A local Social cover is not a valid image: ' . $shortcode . '. The migration will retry next time.');
            return;
        }
    }

    $seed_title_corrections = array(
        'DSfZCbtCjDp' => array(
            'from' => 'На сторожі вашого гаманця',
            'to'   => 'На сторожі вашого кохання',
        ),
        'DMK43ErNyfQ' => array(
            'from' => 'Фінансові послуги по всьому світу',
            'to'   => 'Сервіси, що знаються в якості',
        ),
    );
    $updated                = 0;
    $expected_cover_updates = array();

    foreach ($rows as $row_index => $row) {
        if ('template-social' !== ($row['acf_fc_layout'] ?? '')) {
            continue;
        }

        $posts_key = array_key_exists('field_tower_social_posts', $row) ? 'field_tower_social_posts' : 'posts';
        $posts     = $row[$posts_key] ?? array();
        if (! is_array($posts)) {
            continue;
        }

        foreach ($posts as $post_index => $post) {
            if (! is_array($post)) {
                continue;
            }

            $link = $post['field_tower_social_post_link'] ?? $post['link'] ?? array();
            $url  = is_array($link) ? (string) ($link['url'] ?? '') : (string) $link;
            $code = '';
            foreach (array_keys($cover_ids) as $shortcode) {
                if (str_contains($url, '/reel/' . $shortcode)) {
                    $code = $shortcode;
                    break;
                }
            }
            if ('' === $code) {
                continue;
            }

            $cover = $post['field_tower_social_post_cover'] ?? $post['cover'] ?? '';
            if (empty($cover) && ! empty($cover_ids[$code])) {
                $posts[$post_index]['field_tower_social_post_cover'] = (int) $cover_ids[$code];
                $expected_cover_updates[$row_index][$post_index]     = (int) $cover_ids[$code];
                ++$updated;
            }

            $title_key = array_key_exists('field_tower_social_post_title', $post) ? 'field_tower_social_post_title' : 'title';
            $title     = (string) ($post[$title_key] ?? '');
            $correction = $seed_title_corrections[$code] ?? array();
            if ($title === ($correction['from'] ?? null)) {
                $posts[$post_index][$title_key] = $correction['to'];
                ++$updated;
            }
        }

        $rows[$row_index][$posts_key] = $posts;
    }

    if ($updated) {
        // ACF may return false when nested values are persisted without a
        // top-level meta change, so the read-back below is authoritative.
        update_field('field_tower_constructor', $rows, $page_id);
    }

    $verified_rows = get_field('constructor', $page_id, false);
    foreach ($expected_cover_updates as $row_index => $expected_posts) {
        $verified_row   = $verified_rows[$row_index] ?? array();
        $verified_posts = $verified_row['field_tower_social_posts'] ?? $verified_row['posts'] ?? array();
        foreach ($expected_posts as $post_index => $expected_attachment_id) {
            $verified_post = $verified_posts[$post_index] ?? array();
            $cover         = $verified_post['field_tower_social_post_cover'] ?? $verified_post['cover'] ?? '';
            if ((int) $cover !== $expected_attachment_id) {
                WP_CLI::warning('Could not verify all Social post covers. The migration will retry next time.');
                return;
            }
        }
    }

    update_option('tower_exchange_social_covers_version', $migration_version, false);
    WP_CLI::log(sprintf('Populated or corrected %d Social card value(s).', $updated));
}

tower_bootstrap_migrate_social_post_covers($home_id, $social_cover_ids);

/**
 * Reuse or create a legal page and seed the constructor only when it is empty.
 */
function tower_bootstrap_ensure_text_page(string $slug, string $title, string $content): int
{
    $page = get_page_by_path($slug, OBJECT, 'page');

    if (! $page instanceof WP_Post) {
        $matching_pages = get_posts(
            array(
                'post_type'      => 'page',
                'post_status'    => array('publish', 'draft', 'pending', 'private'),
                'posts_per_page' => 1,
                'title'          => $title,
            )
        );
        $page = $matching_pages ? $matching_pages[0] : null;
    }

    if ($page instanceof WP_Post) {
        $page_id = (int) $page->ID;
    } else {
        $page_id = wp_insert_post(
            array(
                'post_type'    => 'page',
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_content' => '',
            ),
            true
        );
        if (is_wp_error($page_id)) {
            WP_CLI::error($page_id->get_error_message());
        }
        $page_id = (int) $page_id;
    }

    $template = get_page_template_slug($page_id);
    if ('' === $template || 'default' === $template) {
        update_post_meta($page_id, '_wp_page_template', 'page-constructor.php');
        $template = 'page-constructor.php';
    }

    if ('page-constructor.php' === $template) {
        $constructor = get_field('constructor', $page_id, false);
        if (empty($constructor)) {
            update_field(
                'field_tower_constructor',
                array(
                    array(
                        'acf_fc_layout'                         => 'template-simple-text',
                        'field_tower_simple_text_disable'       => 0,
                        'field_tower_simple_text_title'         => $title,
                        'field_tower_simple_text_content'       => $content,
                    ),
                ),
                $page_id
            );
            WP_CLI::log('Prepared the editable text page: ' . $title);
        }
    }

    return $page_id;
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

/**
 * Build a legal menu from real WordPress page objects without replacing edits.
 *
 * @param array<int, int> $page_ids Page IDs in the intended order.
 */
function tower_bootstrap_page_menu(string $name, array $page_ids): int
{
    $menu = wp_get_nav_menu_object($name);
    $menu_result = $menu ? $menu->term_id : wp_create_nav_menu($name);
    if (is_wp_error($menu_result)) {
        WP_CLI::error($menu_result->get_error_message());
    }
    $menu_id = (int) $menu_result;

    $existing_page_ids = array();
    foreach ((array) wp_get_nav_menu_items($menu_id) as $menu_item) {
        if ('post_type' === $menu_item->type && 'page' === $menu_item->object) {
            $existing_page_ids[] = (int) $menu_item->object_id;
        }
    }

    foreach ($page_ids as $page_id) {
        $page_id = (int) $page_id;
        if (! $page_id || in_array($page_id, $existing_page_ids, true)) {
            continue;
        }

        $menu_item_id = wp_update_nav_menu_item(
            $menu_id,
            0,
            array(
                'menu-item-title'     => get_the_title($page_id),
                'menu-item-object-id' => $page_id,
                'menu-item-object'    => 'page',
                'menu-item-status'    => 'publish',
                'menu-item-type'      => 'post_type',
            )
        );
        if (is_wp_error($menu_item_id)) {
            WP_CLI::error($menu_item_id->get_error_message());
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

$legal_pages_migration_version = '2';
if ($legal_pages_migration_version !== (string) get_option('tower_exchange_legal_pages_version', '')) {
    $privacy_page_id = tower_bootstrap_ensure_text_page(
        'privacy-policy',
        'Політика конфіденційності',
        '<p>Інформація про обробку персональних даних буде опублікована після погодження остаточної редакції документа.</p>'
    );
    $terms_page_id = tower_bootstrap_ensure_text_page(
        'terms-of-use',
        'Умови користування',
        '<p>Умови користування сайтом будуть опубліковані після погодження остаточної редакції документа.</p>'
    );

    if (! (int) get_option('wp_page_for_privacy_policy', 0)) {
        update_option('wp_page_for_privacy_policy', $privacy_page_id);
    }

    $legal_menu_id = tower_bootstrap_page_menu(
        'Tower Footer Legal',
        array($privacy_page_id, $terms_page_id)
    );
    $locations = get_theme_mod('nav_menu_locations', array());
    if (empty($locations['footer-legal'])) {
        $locations['footer-legal'] = $legal_menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    update_option('tower_exchange_legal_pages_version', $legal_pages_migration_version, false);
}

WP_CLI::success('Tower Exchange WordPress integration bootstrap completed. Home page ID: ' . $home_id);
