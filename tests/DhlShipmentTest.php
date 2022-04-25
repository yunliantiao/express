<?php

use Txtech\Express\DHLExpress;

/**
 * User : Zelin Ning(NiZerin)
 * Date : 2022/4/25
 * Time : 16:19
 * Email: i@nizer.in
 * Site : nizer.in
 * FileName: DhlShipmentTest.php
 */


class DhlShipmentTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return void
     */
    public function testShipment()
    {
        $dhl = new DHLExpress([
            'url' => 'https://wsbexpress.dhl.com/rest/sndpt',
            'username' => '',
            'password' => 'Z!7jE@6eU@4y',
            'accountCode' => ''
        ]);

        $data = $dhl->generateLabel(json_decode('{"package_id":"966","order_id":"842","order_number":"OEHL9000010001","package_number":"PEHL9000010001","vip_id":"900001","pickup_id":"0","pickup_number":"","pickup_date":null,"PickupConfirmationNumber":null,"Location":null,"PackageReadyTime":null,"CompanyCloseTime":null,"express_code":"BRT","order_type_id":"1","sender":"MASSIMO HU","sender_country":"IT","sender_city":"MILANO","sender_address":"VIA MATTEO MARIA BOIARDO 1","sender_tel":"00393778514165","sender_postcode":"20133","addressee":"SERENA","addressee_tel":"00393288506618","addressee_province":"BS","addressee_city":"BRESCIA","addressee_district":"","addressee_postcode":"00181","addressee_address":"VIALE DELLA STAZIONE 5","addressee_address2":"","addressee_idcard_no":"","addressee_idcard_front":"","addressee_idcard_back":"","created":"2020-08-21 03:59:49","status_id":"1","estimated_weight":"1000000.00","actual_weight":"0.00","is_help_print_label":"0","return_type_id":"102","item_qty":"1","sub_total":"20.00","express_price":"20.00","is_free_tax":"0","packing_size":"0","custom_size":"0.00x0.00x0.00","price_grade":"BRT-01","invoice_url":"","total_value":"20.00","express_global_number":"A000092","express_china_number":"A000092","express_china_name":"","vip_refund_price":"0.00","refund_reason":null,"refund_advice_price":"0.00","apply_refund_time":null,"is_docked":"0","refund_process_user_id":"0","refund_process_time":null,"query_time":"2016-06-28 00:00:00","lon":null,"lat":null,"rand_key":"1uUDE9Cd3248xvOV","backup_lable_number":"","volume":"","rc_plt_number":"","problem_code":"","file_name":null,"express_id":"78","tax_rate_fee_eur":"0.00","express_price_code":"BRT-01","express_price_caption":"全能价格档,1~1000000, €20.00","print_status":"0","is_import_by_excel":"0","is_appointment":"0","is_fixed":"0","sorting_type":"人工","keep_lang":"1","is_removed":"0","source":"WEB ERP","subagent_id":"0","cc_express_id":"0","is_block_package":"0","label_eax_code":"KM","pay_status_id":"4","total_weight_with_box":"2000","box_code":"BRT-01","refund_status_id":"0","calac_type_id":"0","outer_id":"","express_remark":"","audit_success_or_failure":"0","country_code":"IT","is_enabled_insurance":"0","insurance_cost":"0.00","package_content":"1","packaging_type":"1","package_qty":"1","file_declaration":"Correspondence Customs Value","package_purpose":"1","invoice_type":"1","signature":"","tax_number":"1212","package_info":"[{\"id\":\"1\",\"qty\":\"1\",\"weight\":\"2000\",\"volume_weight\":2,\"height_size\":\"20\",\"length_size\":\"20\",\"width_size\":\"20\"}]","free_statement":"","fedex_express_type":"INTERNATIONAL_ECONOMY","fedex_pdf_path":null,"sender_province":"MI","sender_company":null,"sender_references":null,"items":null,"cod_account":"1750541","priceing_code":"100","ship_service_type":"C","email_address":"hubinjie@nle-tech.com","sub_packages":[{"id":"640","package_number":"PEHL9000010001","express_global_number":"A000092","express_china_number":"A000092","barcode":null,"qty":"1","weight":"2000.00","actual_weight":"0.00","height_size":"20","length_size":"20","width_size":"20","volume_weight":"2.00","created":"2020-08-21 03:59:49","fedex_pdf_path":null}],"return_pdf":1}', true));

        \Txtech\Express\Core\Log\Logger::printScreen(\Psr\Log\LogLevel::INFO, 'DHL对接成功', $data);
    }
}