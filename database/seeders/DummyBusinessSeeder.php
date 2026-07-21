<?php

namespace Database\Seeders;

use App\NotificationTemplate;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class DummyBusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        $password = Hash::make('123456');

        $today = Carbon::now()->format('Y-m-d H:i:s');

        // Disable foreign key checks for truncation / insertion
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // ----------------------------------------------------------------------
        // 1. BUSINESS
        // ----------------------------------------------------------------------
        $shortcuts = '{"pos":{"express_checkout":"shift+e","pay_n_ckeckout":"shift+p","draft":"shift+d","cancel":"shift+c","edit_discount":"shift+i","edit_order_tax":"shift+t","add_payment_row":"shift+r","finalize_payment":"shift+f","recent_product_quantity":"f2","add_new_product":"f4"}}';
        $prefixes = '{"purchase":"PO","stock_transfer":"ST","stock_adjustment":"SA","sell_return":"CN","expense":"EP","contacts":"CO","purchase_payment":"PP","sell_payment":"SP","business_location":"BL"}';

        // Product catalogue settings – kept as empty JSON for safety
        $productcatalogue_settings = json_encode([]);

        DB::table('business')->insert([
            [
                'id' => 1,
                'name' => 'Awesome Shop',
                'currency_id' => 2,
                'start_date' => '2018-01-01',
                'tax_number_1' => '3412569900',
                'tax_label_1' => 'GSTIN',
                'tax_number_2' => null,
                'tax_label_2' => null,
                'default_sales_tax' => null,
                'default_profit_percent' => '25.00',
                'owner_id' => 1,
                'time_zone' => 'America/Phoenix',
                'fy_start_month' => 1,
                'accounting_method' => 'fifo',
                'default_sales_discount' => '10.00',
                'sell_price_tax' => 'includes',
                'logo' => null,
                'sku_prefix' => 'AS',
                'enable_product_expiry' => 0,
                'expiry_type' => 'add_expiry',
                'on_product_expiry' => 'keep_selling',
                'stop_selling_before' => 0,
                'enable_tooltip' => 1,
                'purchase_in_diff_currency' => 0,
                'purchase_currency_id' => null,
                'p_exchange_rate' => '1.000',
                'transaction_edit_days' => 30,
                'stock_expiry_alert_days' => 30,
                'keyboard_shortcuts' => $shortcuts,
                'pos_settings' => '{"disable_pay_checkout":0,"disable_draft":0,"disable_express_checkout":0,"hide_product_suggestion":0,"hide_recent_trans":0,"disable_discount":0,"disable_order_tax":0}',
                'enable_brand' => 1,
                'enable_category' => 1,
                'enable_sub_category' => 1,
                'enable_price_tax' => 1,
                'enable_purchase_status' => 1,
                'enable_lot_number' => 0,
                'default_unit' => null,
                'enable_racks' => 0,
                'enable_row' => 0,
                'enable_position' => 0,
                'enable_editing_product_from_purchase' => 1,
                'sales_cmsn_agnt' => null,
                'item_addition_method' => 1,
                'enable_inline_tax' => 1,
                'currency_symbol_placement' => 'before',
                'enabled_modules' => '["purchases","add_sale","pos_sale","stock_transfers","stock_adjustment","expenses","account"]',
                'date_format' => 'm/d/Y',
                'time_format' => '24',
                'repair_settings' => null,
                'ref_no_prefixes' => $prefixes,
                'created_at' => $today,
                'updated_at' => $today,
                'common_settings' => null,
                'productcatalogue_settings' => $productcatalogue_settings,
            ]
        ]);

        // ----------------------------------------------------------------------
        // 2. BUSINESS LOCATION
        // ----------------------------------------------------------------------
        DB::table('business_locations')->insert([
            [
                'id' => 1,
                'business_id' => 1,
                'location_id' => null,
                'name' => 'Awesome Shop',
                'landmark' => 'Linking Street',
                'country' => 'USA',
                'state' => 'Arizona',
                'city' => 'Phoenix',
                'zip_code' => '85001',
                'invoice_scheme_id' => 1,
                'invoice_layout_id' => 1,
                'sale_invoice_layout_id' => 1,
                'selling_price_group_id' => null,
                'print_receipt_on_invoice' => 1,
                'receipt_printer_type' => 'browser',
                'printer_id' => null,
                'mobile' => null,
                'alternate_number' => null,
                'email' => null,
                'website' => null,
                'is_active' => 1,
                'default_payment_accounts' => '{"cash":{"is_enabled":"1","account":null},"card":{"is_enabled":"1","account":null},"cheque":{"is_enabled":"1","account":null},"bank_transfer":{"is_enabled":"1","account":null},"other":{"is_enabled":"1","account":null},"custom_pay_1":{"is_enabled":"1","account":null},"custom_pay_2":{"is_enabled":"1","account":null},"custom_pay_3":{"is_enabled":"1","account":null}}',
                'custom_field1' => null,
                'custom_field2' => null,
                'custom_field3' => null,
                'custom_field4' => null,
                'deleted_at' => null,
                'created_at' => $today,
                'updated_at' => $today,
                'zatca_details' => null,
                'zatca_response' => null,
            ]
        ]);

        // ----------------------------------------------------------------------
        // 3. ADMIN USER
        // ----------------------------------------------------------------------
        DB::table('users')->insert([
            [
                'id' => 1,
                'surname' => 'Mr',
                'first_name' => 'Admin',
                'last_name' => null,
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => $password,
                'language' => 'en',
                'contact_no' => null,
                'address' => null,
                'remember_token' => null,
                'business_id' => 1,
                'is_cmmsn_agnt' => 0,
                'cmmsn_percent' => '0.00',
                'deleted_at' => null,
                'created_at' => $today,
                'updated_at' => $today,
            ]
        ]);

        // ----------------------------------------------------------------------
        // 4. REFERENCE COUNTS (minimal for business 1)
        // ----------------------------------------------------------------------
        DB::table('reference_counts')->insert([
            ['id' => 1, 'ref_type' => 'purchase', 'ref_count' => 1, 'business_id' => 1, 'created_at' => $today, 'updated_at' => $today],
            ['id' => 2, 'ref_type' => 'contacts', 'ref_count' => 1, 'business_id' => 1, 'created_at' => $today, 'updated_at' => $today],
            ['id' => 3, 'ref_type' => 'business_location', 'ref_count' => 1, 'business_id' => 1, 'created_at' => $today, 'updated_at' => $today],
            ['id' => 4, 'ref_type' => 'sell_payment', 'ref_count' => 1, 'business_id' => 1, 'created_at' => $today, 'updated_at' => $today],
        ]);

        // ----------------------------------------------------------------------
        // 5. TAX RATES
        // ----------------------------------------------------------------------
        DB::table('tax_rates')->insert([
            ['id' => 1, 'business_id' => 1, 'name' => 'VAT@10%', 'amount' => 10.00, 'is_tax_group' => 0, 'created_by' => 1, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today],
            ['id' => 2, 'business_id' => 1, 'name' => 'CGST@10%', 'amount' => 10.00, 'is_tax_group' => 0, 'created_by' => 1, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today],
            ['id' => 3, 'business_id' => 1, 'name' => 'SGST@8%', 'amount' => 8.00, 'is_tax_group' => 0, 'created_by' => 1, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today],
            ['id' => 4, 'business_id' => 1, 'name' => 'GST@18%', 'amount' => 18.00, 'is_tax_group' => 1, 'created_by' => 1, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today],
        ]);
        DB::table('group_sub_taxes')->insert([
            ['group_tax_id' => 4, 'tax_id' => 2],
            ['group_tax_id' => 4, 'tax_id' => 3],
        ]);

        // ----------------------------------------------------------------------
        // 6. INVOICE SCHEMES
        // ----------------------------------------------------------------------
        DB::table('invoice_schemes')->insert([
            ['id' => 1, 'business_id' => 1, 'name' => 'Default', 'scheme_type' => 'blank', 'prefix' => 'AS', 'start_number' => 1, 'invoice_count' => 0, 'total_digits' => 4, 'is_default' => 1, 'created_at' => $today, 'updated_at' => $today],
        ]);

        // ----------------------------------------------------------------------
        // 7. INVOICE LAYOUTS
        // ----------------------------------------------------------------------
        DB::table('invoice_layouts')->insert([
            [
                'id' => 1,
                'name' => 'Default',
                'header_text' => null,
                'invoice_no_prefix' => 'Invoice No.',
                'quotation_no_prefix' => null,
                'invoice_heading' => 'Invoice',
                'sub_heading_line1' => null,
                'sub_heading_line2' => null,
                'sub_heading_line3' => null,
                'sub_heading_line4' => null,
                'sub_heading_line5' => null,
                'invoice_heading_not_paid' => '',
                'invoice_heading_paid' => '',
                'quotation_heading' => null,
                'sub_total_label' => 'Subtotal',
                'discount_label' => 'Discount',
                'tax_label' => 'Tax',
                'total_label' => 'Total',
                'total_due_label' => 'Total Due',
                'paid_label' => 'Total Paid',
                'show_client_id' => 0,
                'client_id_label' => null,
                'client_tax_label' => null,
                'date_label' => 'Date',
                'show_time' => 1,
                'show_brand' => 0,
                'show_sku' => 1,
                'show_cat_code' => 1,
                'show_sale_description' => 0,
                'table_product_label' => 'Product',
                'table_qty_label' => 'Quantity',
                'table_unit_price_label' => 'Unit Price',
                'table_subtotal_label' => 'Subtotal',
                'cat_code_label' => null,
                'logo' => null,
                'show_logo' => 0,
                'show_business_name' => 0,
                'show_location_name' => 1,
                'show_landmark' => 1,
                'show_city' => 1,
                'show_state' => 1,
                'show_zip_code' => 1,
                'show_country' => 1,
                'show_mobile_number' => 1,
                'show_alternate_number' => 0,
                'show_email' => 0,
                'show_tax_1' => 1,
                'show_tax_2' => 0,
                'show_barcode' => 0,
                'show_payments' => 1,
                'show_customer' => 1,
                'customer_label' => 'Customer',
                'highlight_color' => '#000000',
                'footer_text' => '',
                'module_info' => null,
                'is_default' => 1,
                'business_id' => 1,
                'design' => 'classic',
                'cn_heading' => null,
                'cn_no_label' => null,
                'cn_amount_label' => null,
                'created_at' => $today,
                'updated_at' => $today,
            ]
        ]);

        // ----------------------------------------------------------------------
        // 8. UNITS (minimal for business 1)
        // ----------------------------------------------------------------------
        DB::table('units')->insert([
            ['id' => 1, 'business_id' => 1, 'actual_name' => 'Pieces', 'short_name' => 'Pc(s)', 'allow_decimal' => 0, 'base_unit_id' => null, 'base_unit_multiplier' => null, 'created_by' => 1, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today],
            ['id' => 2, 'business_id' => 1, 'actual_name' => 'Packets', 'short_name' => 'packets', 'allow_decimal' => 0, 'base_unit_id' => null, 'base_unit_multiplier' => null, 'created_by' => 1, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today],
            ['id' => 3, 'business_id' => 1, 'actual_name' => 'Grams', 'short_name' => 'g', 'allow_decimal' => 1, 'base_unit_id' => null, 'base_unit_multiplier' => null, 'created_by' => 1, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today],
        ]);

        // ----------------------------------------------------------------------
        // 9. SYSTEM SETTINGS
        // ----------------------------------------------------------------------
        $system = [
            ['key' => 'default_business_active_status', 'value' => '1'],
            ['key' => 'superadmin_version', 'value' => config('superadmin.module_version')],
            ['key' => 'app_currency_id', 'value' => '2'],
            ['key' => 'invoice_business_name', 'value' => 'Ultimate POS'],
            ['key' => 'invoice_business_landmark', 'value' => 'Linking Street'],
            ['key' => 'invoice_business_zip', 'value' => '85001'],
            ['key' => 'invoice_business_state', 'value' => 'Arizona'],
            ['key' => 'invoice_business_city', 'value' => 'Phoenix'],
            ['key' => 'invoice_business_country', 'value' => 'USA'],
            ['key' => 'email', 'value' => 'thewebfosters@gmail.com'],
            ['key' => 'enable_offline_payment', 'value' => '1'],
        ];
        foreach ($system as $row) {
            DB::table('system')->where('key', $row['key'])->update(['value' => $row['value']]);
        }

        // ----------------------------------------------------------------------
        // 10. PACKAGES (global, keep all)
        // ----------------------------------------------------------------------
        DB::table('packages')->insert([
            ['id' => 1, 'name' => 'Starter - Free', 'description' => 'Give it a test drive...', 'location_count' => 1, 'user_count' => 2, 'product_count' => 30, 'bookings' => 0, 'kitchen' => 0, 'order_screen' => 0, 'tables' => 0, 'invoice_count' => 30, 'interval' => 'months', 'interval_count' => 1, 'trial_days' => 10, 'price' => '0.0000', 'created_by' => 1, 'sort_order' => 0, 'is_active' => 1, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today, 'custom_permissions' => '{"essentials_module":"1","woocommerce_module":"1"}'],
            ['id' => 2, 'name' => 'Regular', 'description' => 'For Small Shops', 'location_count' => 0, 'user_count' => 0, 'product_count' => 0, 'bookings' => 0, 'kitchen' => 0, 'order_screen' => 0, 'tables' => 0, 'invoice_count' => 0, 'interval' => 'months', 'interval_count' => 1, 'trial_days' => 10, 'price' => '199.9900', 'custom_permissions' => '{"repair_module":"1"}', 'created_by' => 1, 'sort_order' => 1, 'is_active' => 1, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today],
            ['id' => 3, 'name' => 'Unlimited', 'description' => 'For Large Business', 'location_count' => 0, 'user_count' => 0, 'product_count' => 0, 'bookings' => 0, 'kitchen' => 0, 'order_screen' => 0, 'tables' => 0, 'invoice_count' => 0, 'interval' => 'months', 'interval_count' => 1, 'trial_days' => 10, 'price' => '599.9900', 'created_by' => 1, 'sort_order' => 1, 'is_active' => 1, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today, 'custom_permissions' => ''],
            ['id' => 4, 'name' => 'Business', 'description' => 'For Small & Growing Shops...', 'location_count' => 10, 'user_count' => 10, 'product_count' => 15000, 'bookings' => 0, 'kitchen' => 0, 'order_screen' => 0, 'tables' => 0, 'invoice_count' => 1000, 'interval' => 'months', 'interval_count' => 1, 'trial_days' => 10, 'price' => '259.9900', 'created_by' => 1, 'sort_order' => 5, 'is_active' => 0, 'deleted_at' => null, 'created_at' => $today, 'updated_at' => $today, 'custom_permissions' => ''],
        ]);

        // ----------------------------------------------------------------------
        // 11. SUBSCRIPTION (for business 1)
        // ----------------------------------------------------------------------
        $subscription_start = Carbon::today()->subDay(2)->toDateString();
        $subscription_trial = Carbon::today()->addDays(8)->toDateString();
        $subscription_end = Carbon::today()->addDays(28)->toDateString();

        DB::table('subscriptions')->insert([
            [
                'id' => 1,
                'business_id' => 1,
                'package_id' => 3,
                'start_date' => $subscription_start,
                'trial_end_date' => $subscription_trial,
                'end_date' => $subscription_end,
                'package_price' => '599.99',
                'package_details' => '{"location_count":0,"user_count":0,"product_count":0,"invoice_count":0,"name":"Unlimited","woocommerce_module":1, "essentials_module":1}',
                'created_id' => 1,
                'paid_via' => 'stripe',
                'payment_transaction_id' => 'ch_1CuLdQAhokBpT93LVZNg2At6',
                'status' => 'approved',
                'deleted_at' => null,
                'created_at' => $today,
                'updated_at' => $today,
            ]
        ]);

        // ----------------------------------------------------------------------
        // 12. NOTIFICATION TEMPLATES (for business 1)
        // ----------------------------------------------------------------------
        $notification_template_data = NotificationTemplate::defaultNotificationTemplates();
        $notification_template_array = [];
        foreach ($notification_template_data as $notification_template) {
            $notification_template['business_id'] = 1;
            $notification_template_array[] = $notification_template;
        }
        DB::table('notification_templates')->insert($notification_template_array);

        // ----------------------------------------------------------------------
        // 13. ROLES & PERMISSIONS (Admin only)
        // ----------------------------------------------------------------------
        $admin_role = Role::create([
            'name' => 'Admin#1',
            'business_id' => 1,
            'guard_name' => 'web',
            'is_default' => 1,
        ]);

        $admin_user = User::find(1);
        $admin_user->assignRole('Admin#1');

        // Create location permission and assign to admin
        Permission::create(['name' => 'location.1']);
        $admin_user->givePermissionTo('location.1');

        // ----------------------------------------------------------------------
        // Re-enable foreign key checks
        // ----------------------------------------------------------------------
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        DB::commit();
    }
}