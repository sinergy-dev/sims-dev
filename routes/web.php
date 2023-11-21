 <?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/authGoogle','TestController@authGoogle');
// Route::get('/testAfterAuth','TestController@testAfterAuth');
// Route::post('/testAfterAuthSave','TestController@testAfterAuthSave');

Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');


Auth::routes();

Route::post('/update_result', 'SalesController@update_result');
Route::post('/update_result2', 'SalesController@update_result');
Route::post('/update_result2a', 'SalesController@update_result');
Route::post('/update_result3', 'SalesController@update_result');
Route::post('/update_result4', 'SalesController@update_result');
Route::post('/update_result5', 'SalesController@update_result');
Route::get('testBladeNew','TestController@testBladeNew');
Route::get('testMailTimesheet','TestController@testMailTimesheet');
Route::get('testGetWorkDays','PresenceController@getWorkDaysRoute');

Route::get('/testEmailTrap',function(){
	Mail::to('agastya@sinergy.co.id')->send(new App\Mail\TestEmailTrap());
});

Route::get('/create-storage-link', function () {
    Artisan::call('storage:link');
    return 'Tautan simbolik ke folder penyimpanan telah dibuat.';
});

Route::get('testCutiEmail','TestController@mailCuti');
Route::get('testFilter','TestController@testFilter');
Route::get('getWin','TestController@getWin');


Route::get('testEmailReminder','TestController@testEmailReminder');
Route::get('testContribute', 'TestController@testContribute');

Route::get('testRolesShow','TestController@testRole');

Route::get('testPermission','TestController@testPermission');
Route::get('/admin/getPdf', 'PrDraftController@getPdf');
Route::get('/admin/mergePdf', 'PrDraftController@mergePdf');

Route::get('/PMO/downloadProjectCharterPdf','PMProjectController@downloadProjectCharterPdf');
Route::get('/PMO/downloadProgressMeetingPdf','PMProjectController@downloadProgressMeetingPdf');
Route::get('/PMO/downloadFinalProjectPdf','PMProjectController@downloadFinalProjectPdf');

Route::post('/sbe/uploadPdfConfig','SBEController@uploadPdfConfig');
Route::get('/sbe/uploadPdfConfigManual','SBEController@uploadPdfConfigManual');
Route::get('/sbe/getGenerateConfig','SBEController@getGenerateConfig');

// Route::get('testPermissionConfigFeature','TestController@testPermissionConfigFeature');

Route::get('testRole','TestController2@RoleDynamic');
Route::get('shoIndex','TestController2@shoIndex');
Route::get('testRolesShow','TestController@testRole');

Route::get('testGetCutiReportNew','TestController@getReportCuti');

Route::group(['middleware' => ['auth']], function () {
	// tes sales
	Route::get('project/getCountLead','SalesLeadController@getCountLead');
	Route::get('project/index','SalesLeadController@index');
	Route::get('project/getDataLead','SalesLeadController@getDataLead');
	Route::get('project/detailSales/{lead_id}','SalesLeadController@detailSales')->name('detail_project');
	Route::get('detail_project/{lead_id}',function($lead_id){
		return redirect()->route('detail_project', [$lead_id]);
	});
	Route::get('permissionConfig','PermissionConfigController@testPermissionConfig');
	Route::get('project/getPresales', 'SalesLeadController@getPresales');
	Route::get('project/getSales', 'SalesLeadController@getSales');
	Route::get('project/getCustomer', 'SalesLeadController@getCustomer');
	Route::get('project/showEditLead', 'SalesLeadController@showEditLead');
	Route::get('project/getPresalesAssign', 'SalesLeadController@getPresalesAssign');
	Route::post('project/update_lead_register', 'SalesLeadController@update_lead_register');
	Route::get('project/getDetailLead', 'SalesLeadController@getDetailLead');
	Route::get('project/getChangeLog', 'SalesLeadController@getChangeLog');
	Route::get('project/getLeadTp','SalesLeadController@getLeadTp');
	Route::get('project/getLeadSd','SalesLeadController@getLeadSd');
	Route::get('project/getSearchLead', 'SalesLeadController@getSearchDataLead');
	Route::post('project/add_changelog_progress','SalesLeadController@add_changelog_progress');
	Route::post('project/update_tp', 'SalesLeadController@update_tp');
	Route::post('project/update_sd', 'SalesLeadController@update_sd');
	Route::post('project/add_contribute', 'SalesLeadController@add_contribute');
	Route::post('project/changelog_sd', 'SalesLeadController@changelog_sd');
	Route::post('project/changelogTp', 'SalesLeadController@changelogTp');
	Route::get('project/getDetailLeadResult', 'SalesLeadController@getDetailLeadResult');
	Route::get('project/getQuote', 'SalesLeadController@getQuoteNumber');
	Route::post('project/addContribute', 'SalesLeadController@addContribute');
	Route::post('project/updateResult', 'SalesLeadController@updateResult');
	Route::get('project/showTagging','SalesLeadController@showTagging');
	Route::post('project/updateResultRequestPid', 'SalesLeadController@updateResultRequestPid');
	Route::get('project/getPid', 'SalesLeadController@getPid');
	Route::post('project/storeLead', 'SalesLeadController@storeLead');
	Route::post('project/assignPresales', 'SalesLeadController@assignPresales');
	Route::post('project/reassignPresales', 'SalesLeadController@reassignPresales');
	Route::post('project/raiseTender', 'SalesLeadController@raise_to_tender');
	Route::get('project/getTerritory', 'SalesLeadController@getTerritory');
	Route::get('project/getUserByTerritory', 'SalesLeadController@getUserByTerritory');
	Route::get('project/getSalesByTerritory', 'SalesLeadController@getSalesByTerritory');
	Route::get('project/getCompany', 'SalesLeadController@getCompany');
	Route::get('project/getResult', 'SalesLeadController@getResult');	
	Route::get('project/getProductTag','SalesLeadController@getProductTag');
	Route::get('project/getProductTechTag','SalesLeadController@getProductTechTag');
	Route::get('project/getTechTag','SalesLeadController@getTechTag');
	Route::get('project/deleteLead', 'SalesLeadController@destroy');
	Route::get('project/getProductTechTagDetail', 'SalesLeadController@getProductTechTagDetail');
	Route::get('project/getCustomerByLead', 'SalesLeadController@getCustomerbyLead');
	Route::get('project/filterCountLead','SalesLeadController@filterCountLead');
	Route::post('project/checkProductTech', 'SalesLeadController@checkProductTech');
	Route::post('project/updateProductTag', 'SalesLeadController@updateProductTag');
	Route::get('project/showSbeTagging', 'SalesLeadController@showSbe');
	Route::post('project/updateSbeTag', 'SalesLeadController@updateSbeTag');
	Route::post('project/changeNominal', 'SalesLeadController@changeNominal');
	Route::post('project/changeCustomer', 'SalesLeadController@changeCustomer');
	// Route::get('project/getProductTechTag', 'SalesLeadController@getProductTechTag');

	Route::get('/sorry_this_page_is_under_maintenance','DASHBOARDController@maintenance');

	Route::get('/home', 'HomeController@index')->name('home');

	Route::get('/testEmail', 'TestController@send_mail');
	Route::get('/testRemainderEmail', 'TestController@testRemainderEmail');
	Route::get('/testNewLead', 'TestController@testNewLead');
	Route::get('/testAssign', 'TestController@testAssignPresales');
	Route::get('/testTender', 'TestController@testRaiseToTender');
	
	Route::get('/testEmailViewSales', 'TestController@view_mail_to_sales');
	Route::get('/testEmailViewFinance', 'TestController@view_mail_to_finance');
	Route::get('/testEmailPeminjaman','TestController@testEmailPeminjaman');
	Route::get('/testPostEventCalendar','TestController@storeEvents');
	Route::get('/testgetOauth2AccessToken','TestController@getOauth2AccessToken');
	Route::get('/testgetListEvent','TestController@getListEvent');
	Route::get('/testgetCalendarList','TestController@getCalendarList');
	Route::get('/testJson','TestController@testJson');	
	Route::get('/oauth2callback','TestController@oauth2callback');

	//coba calendar team up
	Route::get('/indexCalendar','TestController@indexCalendar');

	Route::get('permission/changeFeatureItem','TestController@changeFeatureItem');
	Route::get('permission/getUserList','TestController@getUserList');
	Route::get('permission/getParameter','TestController@getParameter');
	Route::get('permission/getParameterFeature','TestController@getParameterFeature');
	Route::get('permission/getFeatureRole','TestController@getFeatureRole');
	Route::get('permission/setRoles','TestController@setRoles');
	Route::get('permission/setRolesFeature','TestController@setRolesFeature');
	Route::get('permission/getParameterRoles','TestController@getParameterRoles');
	Route::get('permission/getRoles','TestController@getRoles');
	Route::get('permission/getFeature','TestController@getFeature');
	Route::get('permission/addConfigRoles','TestController@addConfigRoles');
	Route::get('permission/addConfigFeature','TestController@addConfigFeature');
	Route::get('permission/addConfigFeatureItem','TestController@addConfigFeatureItem');
	Route::get('permission/getRoleDetail','TestController@getRoleDetail');
	Route::get('permission/getConfigFeature','TestController@getConfigFeature');
	Route::get('permission/getFeatureItem','TestController@getFeatureItem');
	Route::get('permission/getFeatureItemParameterByRoleGroup','TestController@getFeatureItemParameterByRoleGroup');
	Route::get('permission/getFeatureItemParameterByFeatureItem','TestController@getFeatureItemParameterByFeatureItem');
	Route::get('permission/jsonIconData', 'TestController@getDataIcon');

	Route::get('/data/{id}', 'ImplementationController@get');
	Route::get('/data_pmo', 'PMOController@getGantt');
	Route::get('/exportGantt', 'PMOController@exportGantt');

	// Route::get('custom-login', 'LOGINController@showLoginForm')->name('custom.login');
	// Route::post('custom-attempt', 'LOGINController@attempt')->name('custom.attempt');

	Route::get('/dashboardqoe','DASHBOARDController@indexqoe')->middleware('HRDash');

	//salescontroller
	//customer route
	// Route::get('/customer', 'SalesController@customer_index')->middleware('ManagerStaffMiddleware');
	Route::get('/customer', 'SalesController@customer_index');
	Route::post('/customer/storeRequest', 'SalesController@customer_store');
	Route::get('/customer/getcus','SalesController@getdatacustomer');
	Route::post('/customer/update', 'SalesController@update_customer');
	Route::get('/customer/getCustomerData', 'SalesController@getCustomerData');
	Route::get('/customer/showCustomerRequest', 'SalesController@showCustomerRequest');
	Route::get('/customer/getCustomerDataRequest', 'SalesController@getCustomerDataRequest');
	Route::post('/customer/acceptRequest', 'SalesController@acceptRequest');
	Route::post('/customer/rejectRequest', 'SalesController@rejectRequest');
	Route::get('/delete_customer/{id_customer}', 'SalesController@destroy_customer');

	Route::get('/','DASHBOARDController@index')->middleware('HRDash');
	Route::get('/getDashboardBox','DASHBOARDController@getDashboardBox');
	//notif
	Route::get('/notif_view_all','DASHBOARDController@notif_view_all');

	/*Route::get('/','DASHBOARDController@index')->middleware('Maintenance');*/

	Route::get('/project','SalesController@index')->middleware('ManagerStaffMiddleware');
	/*Route::get('/project','SalesController@index')->middleware('Maintenance');*/
	Route::get('/item','WarehouseController@index');

	Route::get('/show/{lead_id}','SalesController@show');
	Route::get('/getBtnFilter','salescontroller@getBtnFilter');

	//tag product
	Route::get('/sales/tag','ProductTechnologyTag@index');
	Route::post('sales/store/product','ProductTechnologyTag@store_product');
	Route::post('sales/store/tech','ProductTechnologyTag@store_tech');
	Route::post('sales/update/product','ProductTechnologyTag@update_tag_product');
	Route::post('sales/update/technology','ProductTechnologyTag@update_tag_tech');
	Route::get('sales/detail_product','ProductTechnologyTag@detail_product');
	Route::get('sales/detail_tech','ProductTechnologyTag@detail_tech');
	Route::get('sales/getProductEdit','SalesController@getListProductLead');
	Route::get('sales/getProductTag','SalesController@getProductTag');
	Route::get('sales/getProductTechTag','SalesController@getProductTechTag');
	Route::get('sales/getProductTechTagDetail','SalesController@getProductTechTagDetail');
	Route::get('sales/getTechEdit','SalesController@getListTechTag');
	Route::get('sales/getTechTag','SalesController@getTechTag');
	Route::get('sales/getPersonaTags','SalesController@getPersonaTags');
	Route::get('sales/report_product_technology_sip_msp','ReportController@report_product_technology_sip_msp');
	Route::get('sales/update_product_technology','SalesController@update_product_technology');
	Route::get('sales/delete_product_technology','SalesController@delete_product_technology');
	Route::post('sales/add_product_technology','SalesController@add_product_technology');
	Route::get('sales/getAllEmployee','SalesController@getAllEmployee');
	Route::get('sales/getProductTechByLead','SalesController@getProductTechByLead');
	Route::get('sales/getLoseReason','SalesController@getLoseReason');

	// Sales Lead Setting
	Route::get('sales/lead_setting', 'LeadSettingController@index');
	Route::get('sales/lead_setting/getDataLead', 'LeadSettingController@getDataLead');
	Route::get('sales/lead_setting/getDataLeadPerSales', 'LeadSettingController@getDataLeadPerSales');
	Route::get('sales/lead_setting/getDataListSales', 'LeadSettingController@getDataListSales');
	Route::post('sales/lead_setting/postUpdateSales', 'LeadSettingController@postUpdateSales');
	Route::get('sales/lead_setting/getTestMailable', 'LeadSettingController@getTestMailable');

	Route::get('/warehouse', 'WarehouseController@index');
	Route::post('/warehouse/store', 'WarehouseController@store');
	Route::post('/warehouse/update_warehouse', 'WarehouseController@update_warehouse');
	Route::get('/warehouse/delete_item/{item_code}', 'WarehouseController@destroy');

	Route::get('/getChart', 'DASHBOARDController@getChart');
	Route::get('/getPieChart', 'DASHBOARDController@getPieChart');
	Route::get('/getPieChartAFH', 'DASHBOARDController@getPieChartAFH');
	Route::get('/getAreaChart', 'DASHBOARDController@getAreaChart');
	Route::get('/getAreaChart2019', 'DASHBOARDController@getAreaChart2019');
	Route::get('/getAreaChartAdmin', 'DASHBOARDController@getAreaChartAdmin');
	Route::get('/getAreaChartAdmin2018', 'DASHBOARDController@getAreaChartAdmin2018');
	Route::get('/getDoughnutChart', 'DASHBOARDController@getDoughnutChart');
	Route::get('/getDoughnutChartAFH', 'DASHBOARDController@getDoughnutChartAFH');
	Route::get('/getChartByStatus','DASHBOARDController@getChartByStatus');

	Route::get('/getChartAdmin', 'DASHBOARDController@getChartAdmin');

	Route::get('/getCustomer', 'ReportController@getCustomer');
	Route::get('/getCustomerbyDate', 'ReportController@getCustomerbyDate');
	Route::get('/getCustomerbyDate2', 'ReportController@getCustomerbyDate2');
	Route::get('/total_deal_price','ReportController@total_deal_price');

	//PO Customer
	Route::post('/add_po_customer','SalesController@add_po');
	Route::post('/update_po_customer','SalesController@update_po');
	Route::get('/delete_po_customer/{id_tb_po_cus}','SalesController@delete_po');

	Route::get('/pr', 'PrController@index');
	Route::post('/store_pr', 'PrController@store_pr');
	Route::post('/update_pr','PrController@update_pr');
	Route::get('/delete_pr/{id_pr}','PrController@destroy_pr');
	Route::get('prAdmin','PrController@PrAdmin');
	Route::get('downloadExcelPrAdmin','PrController@downloadExcelPr');
	Route::get('/getfilteryearpr', 'PrController@getfilteryear');
	Route::get('/getdatapr', 'PrController@getdatapr');
	Route::get('/getCountPr', 'PrController@getCountPr');
	Route::get('/reportPr', 'PrController@reportPr');
	Route::get('/getTotalPrbyType', 'PrController@getTotalPr');
	Route::get('/getTotalPrByMonth', 'PrController@getTotalPrByMonth');
	Route::get('/getTotalAmountByType', 'PrController@getTotalAmountByType');
	Route::get('/getAmountByCategory', 'PrController@getAmountByCategory');
	Route::get('/getTotalNominalByCat', 'PrController@getTotalNominalByCat');
	Route::get('/getTotalNominalByPid', 'PrController@getTotalNominalByPid');
	Route::get('/getTotalNominalByCatIpr', 'PrController@getTotalNominalByCatIpr');
	Route::get('/getTotalNominalByCatEpr', 'PrController@getTotalNominalByCatEpr');
	Route::get('/getTopFiveSupplier', 'PrController@getTopFiveSupplier');

	Route::get('/getTotalPrbyTypeYear', 'PrController@getTotalPrYear');
	Route::get('/getTotalNominalByCatYear', 'PrController@getTotalNominalByCatYear');
	Route::get('/getTotalNominalByPidYear', 'PrController@getTotalNominalByPidYear');
	Route::get('/getTotalNominalByCatIprYear', 'PrController@getTotalNominalByCatIprYear');
	Route::get('/getTotalNominalByCatEprYear', 'PrController@getTotalNominalByCatEprYear');
	Route::get('/getTopFiveSupplierYear', 'PrController@getTopFiveSupplierYear');

	
	Route::get('/getPrByPid', 'PrController@getPrByPid');

	//Draft PR
	Route::get('/admin/draftPR', 'PrDraftController@draftPR');
	Route::get('/admin/draftPR/{id}', 'PrDraftController@draftPR');
	Route::get('/admin/draftPR/addPembanding/{id}', 'PrDraftController@draftPR');
	Route::get('/admin/detail/draftPR/{id}', 'PrDraftController@detailDraftPR');
	Route::get('/admin/getDraftPr', 'PrDraftController@getDraftPr');
	Route::get('/admin/getLead', 'PrDraftController@getLeadRegister');
	Route::get('/admin/getPid', 'PrDraftController@getPid');
	Route::get('/admin/getProductPr', 'PrDraftController@getProductPr');
	Route::get('/admin/getPreviewPr', 'PrDraftController@getPreviewPr');
	Route::get('/admin/getDetailPr', 'PrDraftController@getDetailPr');
	Route::post('/admin/storeDraftPr', 'PrDraftController@storeSupplierPr');
	Route::post('/admin/storeProductPr', 'PrDraftController@storeProductPr');
	Route::post('/admin/storeDokumen', 'PrDraftController@storeDokumen');
	Route::post('/admin/storeLastStepDraftPr', 'PrDraftController@storeLastStepDraftPr');
	Route::post('/admin/cancelDraftPr', 'PrDraftController@cancelDraftPr');
	Route::get('/admin/getQuote', 'PrDraftController@getQuote');
	Route::post('/admin/storeTermPayment', 'PrDraftController@storeTermPayment');
	Route::post('/admin/verifyDraft', 'PrDraftController@verifyDraft');
	Route::post('/admin/storePembandingSupplier', 'PrDraftController@storePembandingSupplier');
	Route::post('/admin/storePembandingProduct', 'PrDraftController@storePembandingProduct');
	Route::post('/admin/storePembandingDokumen', 'PrDraftController@storePembandingDokumen');
	Route::post('/admin/storePembandingTermPayment', 'PrDraftController@storePembandingTermPayment');
	Route::post('/admin/storeLastStepPembanding', 'PrDraftController@storeLastStepPembanding');
	Route::get('/admin/getPembanding', 'PrDraftController@getPembanding');
	Route::get('/admin/getProductPembanding', 'PrDraftController@getProductPembanding');
	Route::get('/admin/getTypePr', 'PrDraftController@getTypePr');
	Route::get('/admin/getPreviewPembanding', 'PrDraftController@getPreviewPembanding');
	Route::post('/admin/choosedComparison', 'PrDraftController@choosedComparison');
	Route::get('/admin/getActivity', 'PrDraftController@getActivity');
	Route::get('/admin/getCountComparing', 'PrDraftController@getCountComparing');
	Route::get('/admin/cekTTD', 'PrDraftController@cekTTD');
	Route::get('/admin/showTTD', 'PrDraftController@showTTD');
	Route::post('/admin/uploadTTD', 'PrDraftController@uploadTTD');
	Route::post('/admin/submitTtdApprovePR', 'PrDraftController@submitTtdApprovePR');
	Route::post('/admin/rejectCirculerPR', 'PrDraftController@rejectCirculerPR');
	Route::post('/admin/circulerPrTanpaPembanding', 'PrDraftController@circulerPrTanpaPembanding');
	Route::post('/admin/circulerPr', 'PrDraftController@circulerPr');
	Route::get('/admin/getDataSendEmail', 'PrDraftController@getDataSendEmail');
	Route::get('/admin/getEmailTemplate', 'PrDraftController@getEmailTemplate');
	Route::get('/admin/getPdfPr', 'PrDraftController@getPdfPr');
	Route::get('/admin/getPdfPRFromLink', 'PrDraftController@getPdfPRFromLink');
	Route::get('/admin/getOnlyPdfPRFromLink', 'PrDraftController@getOnlyPdfPRFromLink');
	Route::post('/admin/sendMailtoFinance', 'PrDraftController@sendMailtoFinance');
	Route::post('/admin/deleteDokumen', 'PrDraftController@deleteDokumen');
	Route::post('/admin/deleteProduct', 'PrDraftController@deleteProduct');
	Route::post('/admin/updateSupplier', 'PrDraftController@updateSupplierPr');
	Route::post('/admin/updateProductPr', 'PrDraftController@updateProductPr');
	Route::get('/admin/sendMailDraft', 'PrDraftController@sendMailDraft');
	Route::get('/admin/getSignStatusPR','PrDraftController@getSignStatusPR');
	Route::get('/admin/getCount','PrDraftController@getCount');
	Route::get('/admin/getFilterDraft','PrDraftController@getFilterDraft');
	Route::get('/admin/getFilterStatus','PrDraftController@getFilterStatus');
	Route::get('/admin/getFilterUser','PrDraftController@getFilterUser');
	Route::get('/admin/getFilterCount','PrDraftController@getFilterCount');
	Route::post('/admin/storeNotes', 'PrDraftController@storeNotes');
	Route::post('/admin/storeReply', 'PrDraftController@storeReply');
	Route::post('/admin/storeResolveNotes', 'PrDraftController@storeResolveNotes');
	Route::get('/admin/getNotes', 'PrDraftController@getNotes');
	Route::get('/admin/getProductById', 'PrDraftController@getProductById');
	Route::get('/admin/getProductCompareById', 'PrDraftController@getProductCompareById');
	Route::post('/admin/uploadCSV','PrDraftController@uploadCSV');
	Route::get('/admin/getPidAll', 'PrDraftController@getPidAll');
	Route::get('/admin/getLeadByPid', 'PrDraftController@getLeadByPid');
	Route::get('/admin/getPerson', 'PrDraftController@getPerson');
	Route::post('admin/storeTax', 'PrDraftController@storeTax');
	Route::post('admin/storeTaxComparing', 'PrDraftController@storeTaxComparing');
	Route::get('admin/getSupplier', 'PrDraftController@getSupplier');
	Route::get('admin/getDropdownFilterPr', 'PrDraftController@getDropdownFilterPr');
	Route::get('admin/getSupplierDetail', 'PrDraftController@getSupplierDetail');
	Route::get('admin/getPidUnion', 'PrDraftController@getSupplierDetail');

	Route::get('/po', 'PONumberController@index');
	Route::get('/getPRNumber', 'PONumberController@getPRNumber');
	Route::post('/store_po', 'PONumberController@store');
	Route::post('/update_po','PONumberController@update');
	Route::get('/delete_po/{id_po}','PONumberController@destroy');
	Route::get('/getDataPrforPo', 'PONumberController@getdatapr');
	Route::get('/getdatapo', 'PONumberController@getdatapo');
	Route::get('/getfilteryearpo', 'PONumberController@getfilteryear');

	Route::post('/add_contribute','SalesController@add_contribute');
	Route::post('/add_contribute_pmo','PMOController@add_contribute');
	Route::post('/add_contribute_engineer','EngineerController@add_contribute');

	Route::get('/view_lead', 'ReportController@view_lead');
	Route::get('/view_open', 'ReportController@view_open');
	Route::get('/view_win', 'ReportController@view_win');
	Route::get('/view_lose', 'ReportController@view_lose');

	Route::get('/report', 'ReportController@report');
	Route::get('/report_range', 'ReportController@report_range');
	Route::get('/report_range/{nik}', 'ReportController@report_range');
	Route::get('/filter_sales_report','ReportController@filter_sales_report');
	Route::get('/report_deal_price', 'ReportController@report_deal_price');
	Route::get('/report_sales', 'ReportController@report_sales');
	Route::get('/getfiltertop', 'ReportController@getfiltertop');
	Route::get('/getfiltertopmsp', 'ReportController@getfiltertopmsp');
	Route::get('/report_presales', 'ReportController@report_presales');
	Route::get('/report_customer', 'ReportController@report_customer');
	Route::get('/getreportterritory', 'ReportController@getreportterritory');
	Route::get('/getFilterDateTerritory','ReportController@getFilterDateTerritory');
	Route::get('/getFilterTerritoryTabs','ReportController@getFilterTerritoryTabs');
	Route::get('/report_excel_presales', 'ReportController@download_excel_presales_win');
	Route::get('/report_product_technology','ReportController@report_product_technology');
	Route::get('/getFilterTags','ReportController@getFilterTags');
	Route::get('/get_data_sd_report_sales', 'ReportController@get_data_sd_report_sales');
	Route::get('/get_data_tp_report_sales', 'ReportController@get_data_tp_report_sales');
	Route::get('/get_data_win_report_sales', 'ReportController@get_data_win_report_sales');
	Route::get('/get_data_lose_report_sales', 'ReportController@get_data_lose_report_sales');
	Route::get('/getReportExcelReportRange', 'ReportController@downloadExcelReportRange');
	Route::get('/reportExcelTag', 'ReportController@reportExcelTag');
	Route::get('/reportPdfTag','TestController@reportPdfTag');
	Route::get('/get_top_win_sip', 'ReportController@get_top_win_sip');
	Route::get('/get_top_win_msp', 'ReportController@get_top_win_msp');
	Route::get('/get_filter_top_win_sip', 'ReportController@get_filter_top_win_sip');
	Route::get('/get_filter_top_win_msp', 'ReportController@get_filter_top_win_msp');
	Route::get('/getCustomerPerTerritory', 'ReportController@getCustomerPerTerritory');
	


	Route::get('/report_product_index','ReportController@report_product_index');
	Route::get('/getreportproduct','ReportController@getreportproduct');
	Route::get('/getTerritory', 'ReportController@getTerritory');
	Route::get('/getFilterProduct', 'ReportController@getFilterProduct');

	//route report customer msp
	Route::get('/getreportcustomermsp','ReportController@getreportcustomermsp');
	Route::get('/getfiltercustomermsp','ReportController@getfiltercustomermsp');

	Route::get('/downloadPdfreport', 'ReportController@downloadPdfreport');
	Route::get('/downloadPdflead', 'ReportController@downloadPdflead');
	Route::get('/downloadPdfopen', 'ReportController@downloadPdfopen');
	Route::get('/downloadPdfwin', 'ReportController@downloadPdfwin');
	Route::get('/downloadPdflose', 'ReportController@downloadPdflose');

	Route::get('/downloadReportPDF', 'ReportController@downloadReportPDF');

	Route::get('/exportExcelLead', 'ReportController@exportExcelLead');
	Route::get('/exportExcelOpen', 'ReportController@exportExcelOpen');
	Route::get('/exportExcelWin', 'ReportController@exportExcelWin');
	Route::get('/exportExcelLose', 'ReportController@exportExcelLose');

	Route::get('/getfiltersd', 'ReportController@getfiltersd');
	Route::get('/getfiltertp', 'ReportController@getfiltertp');
	Route::get('/getfilterwin', 'ReportController@getfilterwin');
	Route::get('/getfilterlose', 'ReportController@getfilterlose');

	Route::get('/getfiltersdyear', 'ReportController@getfiltersdyear');
	Route::get('/getfiltertpyear', 'ReportController@getfiltertpyear');
	Route::get('/getfilterwinyear', 'ReportController@getfilterwinyear');
	Route::get('/getfilterloseyear', 'ReportController@getfilterloseyear');


	Route::get('/getfiltersdpresales', 'ReportController@getfiltersdpresales');
	Route::get('/getfiltertppresales', 'ReportController@getfiltertppresales');
	Route::get('/getfilterwinpresales', 'ReportController@getfilterwinpresales');
	Route::get('/getfilterlosepresales', 'ReportController@getfilterlosepresales');

	Route::get('/getfiltersdyearpresales', 'ReportController@getfiltersdyearpresales');
	Route::get('/getfiltertpyearpresales', 'ReportController@getfiltertpyearpresales');
	Route::get('/getfilterwinyearpresales', 'ReportController@getfilterwinyearpresales');
	Route::get('/getfilterloseyearpresales', 'ReportController@getfilterloseyearpresales');
	Route::get('/getfilteryearpresales', 'ReportController@getfilteryearpresales');

	Route::get('/filter_lead_presales', 'ReportController@filter_lead_presales');
	Route::get('/getdatalead', 'ReportController@getdatalead');
	Route::get('/filter_presales_each_year', 'ReportController@filter_presales_each_year');
	Route::get('/get_lead_init_presales', 'ReportController@getdatainitleadpresales');

	//Record Authentication
	Route::get('/report_record_auth', 'ReportController@report_record_auth');
	Route::get('/get_login','ReportController@get_auth_login');
	Route::get('/get_logout','ReportController@get_auth_logout');
	Route::get('/get_auth_login_users','ReportController@get_auth_login_users');
	Route::get('/getFilterRecordAuth','ReportController@getFilterRecordAuth');

	/*Route::get('/presales_manager','PRESALES_MANAGERController@index');
	Route::post('/presales/store', 'PRESALES_MANAGERController@store');*/

	Route::get('/po', 'PONumberController@index');
	Route::post('/store_po', 'PONumberController@store');
	Route::post('/update_po','PONumberController@update');
	Route::get('/delete_po/{id_po}','PONumberController@destroy');

	Route::get('/client', 'ReportController@getDropdown');
	Route::get('/assign_quote', 'SalesController@getDropdown');
	Route::get('/data_lead', 'SalesController@getDatalead');
	Route::get('/getIdTask', 'HRGAController@getIdTask');
	Route::get('/sho','SHOController@index');
	Route::get('/detail_sho/{id_sho}','SHOController@detail_sho');
	Route::post('/update_sho','SHOController@update_sho');
	Route::post('/update_sho_transac','SHOController@update_sho_transac');

	Route::get('/dropdownTech', 'HRController@getDropdownTech');
	Route::get('/hu_rec','HRController@index')->middleware('HRMiddleware');
	// Route::get('/hu_rec','HRController@index');
	Route::post('/hu_rec/store', 'HRController@store');
	Route::get('/hu_rec/store', 'HRController@store');
	// Route::get('/hu_rec/store', function(){
	// 	return 'b';
	// });
	Route::post('/hu_rec/update', 'HRController@update_humanresource');
	Route::get('delete_hr/{nik}', 'HRController@destroy_hr')->middleware('HRMiddleware');
	Route::get('/profile_user','HRController@user_profile');
	Route::post('/update_profile','HRController@update_profile');
	Route::post('/profile/delete_pict','HRController@delete_pict');
	Route::get('/hu_rec/get_hu','HRController@getdatahu');
	Route::get('/exportExcelEmployee', 'HRController@exportExcelEmployee');
	Route::get('/exportExcelResignEmployee', 'HRController@exportExcelResignEmployee');
	Route::get('/guideLine','HRController@GuideLineIndex');
	Route::get('/storeGuide','HRController@storeGuideLine');
	Route::get('/updateGuide','HRController@updateGuideLine');
	Route::get('/deleteGuide','HRController@deleteGuideLine');
	Route::get('/getGuideIndex','HRController@getGuideIndex');
	Route::get('/getGuideIndexById','HRController@getGuideIndexById');
	Route::post('/update_profile_npwp','HRController@update_profile_npwp');

	//cuti
	Route::get('/show_cuti', 'HRGAController@show_cuti');
	Route::post('/store_cuti','HRGAController@store_cuti');
	Route::POST('/update_cuti','HRGAController@update_cuti');
	Route::post('/approve_cuti','HRGAController@approve_cuti');
	Route::post('/decline_cuti','HRGAController@decline_cuti');
	Route::get('/detilcuti','HRGAController@detil_cuti');
	Route::get('/downloadCutiReport','HRGAController@CutiExcel');
	Route::get('/getfilterCutiByDate','HRGAController@filterByDate');
	Route::get('/getfilterCutiByDateDiv','HRGAController@filterByDateDiv');
	Route::get('/getfilterCutiByDiv','HRGAController@filterByDiv');
	Route::post('/setting_total_cuti','HRGAController@setting_total_cuti');
	Route::post('/set_total_cuti','HRGAController@set_total_cuti');
	Route::get('/getCutiUsers','HRGAController@getCutiUsers');
	Route::get('/getCutiAuth','HRGAController@getCutiAuth');
	Route::get('/delete_cuti/{id_cuti}', 'HRGAController@delete_cuti');
	Route::get('/follow_up/{id_cuti}', 'HRGAController@follow_up');
	Route::get('/downloadPdfcuti', 'HRGAController@cutipdf');
	Route::get('/get_list_cuti', 'HRGAController@get_list_cuti');
	Route::get('/get_cuti_byMonth', 'HRGAController@get_request_cuti_byMonth');
	Route::get('/getFilterCom', 'HRGAController@getFilterCom');
	Route::get('/get_history_cuti', 'HRGAController@get_history_cuti');
	Route::get('/getPublicHolidayAdjustment','HRGAController@getPublicHolidayAdjustment');
	Route::post('/storeCutiAddition','HRGAController@storeCutiAddition');

	Route::get('/index_delivery_person', 'HRGAController@index_delivery_person');
	Route::get('/detail_delivery_person/{id_messenger}', 'HRGAController@detail_delivery_person');
	Route::get('/getDataMessenger','HRGAController@getDataMessenger');
	Route::get('/getDateMessenger','HRGAController@getDateMessenger');
	Route::get('/getMessenger','HRGAController@getMessenger');
	Route::get('/delete_messenger/{id_messenger}','HRGAController@delete_messenger');
	Route::post('/update_messenger','HRGAController@update_messenger');
	Route::post('/update_progress','HRGAController@update_progress');
	Route::post('/store_messenger','HRGAController@store_messenger');

	Route::post('/store_sho','SHOController@store');
	Route::post('/store_sho_transac','SHOController@store_sho_transac');

	Route::get('/quote', 'QuoteController@index');

	Route::get('/add', 'QuoteController@create');
	Route::post('/quote/store', 'QuoteController@store');
	Route::post('/quote/update', 'QuoteController@update');
	Route::get('/report_quote', 'QuoteController@report_quote');
	Route::get('delete', 'QuoteController@destroy_quote');
	Route::post('/store_quotebackdate', 'QuoteController@store_backdate');
	Route::get('/downloadExcelQuote', 'QuoteController@donwloadExcelQuote');
	Route::get('/getdataquote', 'QuoteController@getdataquote');
	Route::get('/getdatabackdatequote', 'QuoteController@getdatabackdate');
	Route::get('/getfilteryearquote', 'QuoteController@getfilteryear');
	Route::get('/get_backdate_num', 'QuoteController@get_backdate_num');
	Route::get('/quote/getCustomer', 'QuoteController@getCustomer');
	Route::post('/addBackdateNumQuote', 'QuoteController@addBackdateNum');

	Route::get('/delete_detail_sho/{id_transaction}', 'SHOController@destroy_detail');

	Route::get('/profile','HRController@edit_password');
	Route::post('/changePassword','HRController@changePassword');
	Route::post('/resetPassword', 'HRController@resetPassword');
	Route::get('/salesproject', 'SalesController@sales_project_index');
	Route::get('/getsalesproject', 'SalesController@getsalesproject');
	Route::get('/getPIDIndex','SalesController@getPIDIndex');
	Route::get('/getEditPID','SalesController@getEditPID');
	Route::get('/getShowPIDReq','SalesController@getShowPIDReq');
	Route::get('/getFilterYearPID','SalesController@getFilterYearPID');


	Route::get('/salesproject/getRequestProjectID', 'SalesController@getRequestProjectID');
	Route::get('/salesproject/submitRequestID', 'SalesController@submitRequestID');

	Route::get('/salesproject/getAcceptProjectID', 'SalesController@getAcceptProjectID');

	Route::get('/store_sp', 'SalesController@store_sales_project');
	Route::post('/update_sp', 'SalesController@update_sp');
	Route::post('/update_status_sp', 'SalesController@update_status_sales_project');
	Route::get('/delete_project/{id_pro}', 'SalesController@destroy_sp');
	Route::get('/detail_sales_project/{id_pro}','SalesController@detail_sales_project');
	Route::get('/getleadpid','SalesController@getleadpid');
	Route::post('/update_result_idpro', 'SalesController@update_result_request_id');

	Route::post('/engineer_assign','EngineerController@store');

	Route::post('/update_cek/{id_transaction}','SHOController@update_detail_sho');

	Route::get('/getMountNow',function(){

		echo date("n");

	});

	Route::post('/assign_to_pmo','PMOController@store');
	Route::post('/reassign_to_pmo','PMOController@update_pmo');
	Route::get('/delete_contribute_pmo/{id_pmo}', 'PMOController@destroy');
	Route::get('/delete_contribute_engineer/{id_engineer}','EngineerController@delete_contribute_engineer');
	Route::get('/delete_contribute_sd','SalesController@delete_contribute_sd');
	Route::post('/reassign_to_engineer','EngineerController@reassign_engineer');

	Route::post('/pmo_progress','PMOController@progress_store');
	Route::post('/engineer_progress','EngineerController@progress_store');
	Route::post('/update_status_eng','EngineerController@update_status_engineer');

	// Route::get('/hrga','HRGAController@index');
	Route::get('/add_barang', 'HRGAController@create');
	Route::post('/store_barang', 'HRGAController@store');
	Route::get('/delete/{id}', 'HRGAController@destroy');
	Route::get('/edit/{id}', 'HRGAController@edit');
	Route::post('/barang/update', 'HRGAController@update');
	Route::post('/store_task','EngineerController@store_task');
	Route::post('/done_task','EngineerController@done_task');

	Route::get('/downloadPdfPMO', 'PMOController@downloadPDF');

	Route::get('/export', 'SalesController@export');
	Route::get('/export_msp', 'SalesController@export_msp');
	Route::get('/exportExcel', 'PMOController@exportExcel');

	// Config Management
	Route::get('/config_management','CMController@index');
	Route::post('/store_cm', 'CMController@store');
	Route::get('/delete_cm/{no}', 'CMController@destroy');
	Route::post('/update_cm', 'CMController@update');

	Route::get('/downloadPdfCM', 'CMController@downloadPDF');
	Route::get('/exportExcelCM', 'CMController@exportExcel');

	//PR asset management
	Route::get('/pr_asset','PAMController@index');
	Route::post('/store_pr_asset', 'PAMController@tambah');
	Route::post('/store_produk', 'PAMController@store_produk');
	Route::get('/delete_pr_asset', 'PAMController@destroy');
	Route::post('/edit_pr_asset', 'PAMController@update');
	Route::post('/assign_to_hrd_pr_asset','PAMController@assign_to_hrd');
	Route::post('/assign_to_fnc_pr_asset','PAMController@assign_to_fnc');
	Route::post('/assign_to_adm_pr_asset','PAMController@assign_to_adm');
	Route::post('/tambah_return_hr_pr_asset', 'PAMController@tambah_return_hr');
	Route::post('/tambah_return_fnc_pr_asset', 'PAMController@tambah_return_fnc');
	Route::get('/add_pam', 'PAMController@add_pam');
	Route::post('/store_produk_cus', 'PAMController@store_produk_cus');

	Route::get('/detail_pam/{id_pam}', 'PAMController@detail_pam');

	Route::get('/downloadPdfPR', 'PAMController@downloadPDF');
	Route::get('/downloadPdfPR2/{id_pam}', 'PAMController@downloadPDF2');
	Route::get('/exportExcelPR', 'PAMController@exportExcel');

	//Incident Management
	Route::get('/incident_management', 'INCIDENTController@index');
	Route::get('/add_incident', 'INCIDENTController@create');
	Route::post('/store_incident', 'INCIDENTController@store');
	Route::get('/delete_incident/{no}', 'INCIDENTController@destroy');
	Route::get('/edit_incident/{no}', 'INCIDENTController@edit');
	Route::post('/update_incident', 'INCIDENTController@update');
	Route::get('/read/{no}', 'INCIDENTController@show');
	Route::get('/downloadPdfIM', 'INCIDENTController@downloadPDF');
	Route::get('/exportExcelIM', 'INCIDENTController@exportExcelIM');
	//Engineer Management
	Route::get('/esm', 'ESMController@index');
	Route::get('/getESM','ESMController@getESM');
	Route::get('/getEditEsm','ESMController@getEditEsm');
	Route::get('/getFilterESMbyYear','ESMController@getFilterESMbyYear');
	Route::get('/getFilterESMbyStatus','ESMController@getFilterESMbyStatus');
	Route::post('/store_esm', 'ESMController@store');
	Route::get('/delete_esm/{id_ems}', 'ESMController@destroy');
	Route::post('/edit_esm', 'ESMController@edit');
	Route::get('/downloadPdfESM', 'ESMController@downloadpdf');
	Route::get('/downloadExcelESM', 'ESMController@downloadExcel');
	Route::post('/assign_to_hrd', 'ESMController@assign_to_hrd');
	Route::post('/assign_to_fnc', 'ESMController@assign_to_fnc');
	Route::post('/assign_to_adm', 'ESMController@assign_to_adm');
	Route::get('/detail_esm/{no}', 'ESMController@detail_esm');
	Route::post('/tambah_return_hr', 'ESMController@tambah_return_hr');
	Route::post('/tambah_return_fnc', 'ESMController@tambah_return_fnc');
	Route::get('/claim_pending', 'ESMController@claim_pending');
	Route::get('/claim_transfer', 'ESMController@claim_transfer');
	Route::get('/claim_admin', 'ESMController@claim_admin');
	Route::post('import_claim', 'ESMController@import_claim');
	Route::post('import_claim_progress', 'ESMController@import_claim_progress');

	Route::get('/downloadExcelPO', 'PONumberController@downloadExcelPO');
	Route::get('/downloadExcelPr', 'PrController@downloadExcelPr');

	Route::get('/letter', 'LetterController@index');
	Route::post('/store_letter', 'LetterController@store');
	Route::post('/update_letter', 'LetterController@edit');
	Route::get('/delete_letter/{id}', 'LetterController@destroy');
	Route::get('/downloadExcelLetter', 'LetterController@downloadExcel');
	Route::post('/store_letterbackdate', 'LetterController@store_backdate');
	Route::get('/getdataletter', 'LetterController@getdataletter');
	Route::get('/getfilteryearletter', 'LetterController@getfilteryear');
	Route::get('/get_backdate_letter', 'LetterController@get_backdate_num');
	Route::post('/addBackdateNumLetter', 'LetterController@addBackdateNum');


	Route::get('/do', 'DONumberController@index');
	Route::post('/store_do', 'DONumberController@store');
	Route::post('/update_do', 'DONumberController@update');
	Route::get('/getdatado', 'DONumberController@getdatado');
	Route::get('/getfilteryeardo', 'DONumberController@getfilteryear');
	Route::get('/downloadExcelDO', 'DONumberController@downloadExcelDO');

	Route::get('/partnership', 'PartnershipController@index');
	Route::get('/partnership_detail/{id}', 'PartnershipController@detail');
	Route::post('/store_partnership', 'PartnershipController@store');
	Route::post('/update_partnership', 'PartnershipController@update');
	Route::get('/downloadPdfpartnership', 'PartnershipController@downloadpdf');
	Route::get('/downloadExcelPartnership','PartnershipController@downloadExcel');
	Route::get('/delete_partnership/{id}', 'PartnershipController@destroy');
	Route::post('/upload/proses', 'PartnershipController@proses_upload');
	Route::get('/download_partnership/{id}', 'PartnershipController@download_partnership');
	Route::get('/partnership/getUser', 'PartnershipController@getUser');
	Route::get('/partnership/getDetail', 'PartnershipController@getDetailPartnership');
	Route::get('/partnership/getCert', 'PartnershipController@getListCert');
	Route::post('/partnership/addCertList', 'PartnershipController@addCertList');
	Route::post('/partnership/addCert', 'PartnershipController@addCert');
	Route::post('/partnership/updateCertPerson', 'PartnershipController@updateCertPerson');
	Route::post('/partnership/deleteCertPerson', 'PartnershipController@deleteCertPerson');
	Route::get('/partnership/getDataPartnership', 'PartnershipController@getDataPartnership');
	Route::get('/partnership/getTargetPartnership', 'PartnershipController@getTargetPartnership');
	Route::get('/partnership/getCertPartnership', 'PartnershipController@getCertPartner');
	Route::post('/partnership/store_target', 'PartnershipController@store_target');
	Route::get('/partnership/getDataLog', 'PartnershipController@getDataLog');
	Route::post('/partnership/updateStatusTarget', 'PartnershipController@updateStatusTarget');
	Route::post('/partnership/deleteTarget', 'PartnershipController@deleteTarget');
	Route::post('/partnership/deleteCertPartner', 'PartnershipController@deleteCertPartner');
	Route::post('/partnership/updateTitleCert', 'PartnershipController@updateTitleCert');
	Route::get('/partnership/getTargetById', 'PartnershipController@getTargetById');
	Route::post('/partnership/updateTarget', 'PartnershipController@updateTarget');
	Route::get('/partnership/getSearchDataPartnership', 'PartnershipController@getSearchDataPartnership');
	Route::get('/partnership/getCountDashboard', 'PartnershipController@getCountDashboard');
	Route::get('/partnership/getCertByBrand', 'PartnershipController@getCertByPartner');
	Route::get('/partnership/getCertByCategory', 'PartnershipController@getCertByCategory');
	Route::get('/partnership/getToDoList', 'PartnershipController@getToDoList');
	Route::get('/partnership/getNeedAttention', 'PartnershipController@getNeedAttention');

	Route::get('/admin_hr', 'HRNumberController@index');
	Route::post('/store_admin_hr', 'HRNumberController@store');
	Route::post('/update_admin_hr', 'HRNumberController@update');
	Route::get('/delete_admin_hr', 'HRNumberController@destroy');
	Route::get('/downloadExcelAdminHR', 'HRNumberController@downloadExcelAdminHR');
	Route::get('/getdatahrnumber', 'HRNumberController@getdata');
	Route::get('/getfilteryearhrnumber', 'HRNumberController@getFilteryear');

	//Inventory
	Route::post('/inventory/store', 'WarehouseController@inventory_store');
	Route::post('/inventory/update', 'WarehouseController@inventory_update');
	Route::post('/inventory/store_detail_produk', 'WarehouseController@inventory_detail_produk');
	Route::get('/inventory', 'WarehouseController@inventory_index');
	Route::post('/warehouse/inventory_update', 'WarehouseController@inventory_update');
	Route::get('/detail_inventory/{id_product}','WarehouseController@Detail_inventory');
	Route::post('/update_serial_number','WarehouseController@update_serial_number');
	Route::get('/warehouse/destroy_produk/{id_barang}', 'WarehouseController@destroy_produk');
	Route::get('/delete_id_detail','WarehouseController@destroy_detail_produk');
	Route::get('/dropdownPO', 'WarehouseController@getDropdownPO');
	Route::get('/dropdownPoSIP', 'WarehouseController@getDropdownPoSIP');
	Route::get('/dropdownSubmitPO', 'WarehouseController@getDropdownSubmitPO');
	Route::get('/dropdownSubmitPoSIP', 'WarehouseController@getDropdownSubmitPoSIP');
	Route::get('/getbtnSN', 'WarehouseController@getbtnSN');
	Route::get('/inventoryWarehouse','WarehouseController@view_inventory');
	Route::get('/do-sup/index', 'WarehouseController@do_sup_index');
	Route::post('/approve_finance_do','WarehouseController@approve_finance_do');

	//Project
	Route::get('/inventory/project', 'WarehouseProjectController@index');
	Route::get('/add/project_delivery', 'WarehouseProjectController@add_project_delivery');
	Route::get('/add/do_sip', 'WarehouseProjectController@add_project_sip');
	Route::post('/inventory/store_project_inventory', 'WarehouseProjectController@store_project_inventory');
	Route::get('/dropdownProject', 'WarehouseProjectController@getDropdown');
	Route::get('/dropdownDetailProject', 'WarehouseProjectController@getDetailProduk');
	Route::get('/doWarehouse','WarehouseProjectController@view_do');
	// Route::post('/inventory/project/store', 'WarehouseProjectController@project_store');
	// Route::post('/return_do_product_msp', 'WarehouseProjectController@return_do_product_msp');
	// Route::post('/edit_qty_do', 'WarehouseProjectController@edit_qty_do');
	// Route::get('/detail_project_inventory/{id_transaction}','WarehouseProjectController@Detail_project');
	// Route::get('/getDropdownSubmit','WarehouseProjectController@getDropdownSubmit');
	Route::post('/store_delivery_sip', 'WarehouseProjectController@store_delivery_sip');
	Route::post('/return_do_product_msp', 'WarehouseProjectController@return_do_product_msp');
	Route::post('/edit_qty_do', 'WarehouseProjectController@edit_qty_do');
	Route::get('/detail_project_inventory/{id_inventory_project}','WarehouseProjectController@Detail_project');
	Route::get('/getDropdownSubmit','WarehouseProjectController@getDropdownSubmit');
	Route::get('/showDetail','WarehouseProjectController@ShowDetailProduk');

	//category+type
	Route::get('/category', 'WarehouseController@category_index');
	Route::post('/store_category', 'WarehouseController@store_category');
	Route::post('/store_type', 'WarehouseController@store_type');
	Route::post('/update_category', 'WarehouseController@update_category');
	Route::post('/update_type', 'WarehouseController@update_tipe');

	Route::get('/asset', 'WarehouseAssetController@index');
	Route::get('/assetWarehouse', 'WarehouseAssetController@view_asset');
	Route::post('/store_asset_warehouse', 'WarehouseAssetController@store');
	Route::post('/update_asset_warehouse', 'WarehouseAssetController@edit');
	Route::get('/delete_asset_warehouse/{id_barang}', 'WarehouseAssetController@destroy');
	Route::post('/update_peminjaman_asset', 'WarehouseAssetController@peminjaman');
	Route::post('/accept_pinjam_warehouse', 'WarehouseAssetController@accept_pinjam');
	Route::post('/reject_pinjam_warehouse', 'WarehouseAssetController@reject');
	Route::post('/ambil_pinjam_warehouse', 'WarehouseAssetController@ambil');
	Route::post('/kembali_pinjam_warehouse', 'WarehouseAssetController@kembali');
	Route::get('/detail_asset/{id_barang}','WarehouseAssetController@detail_asset');
	Route::get('/getKategori2','AssetController@getKategori2');
	Route::get('/exportExcelTech','AssetController@exportExcelTech');
	Route::get('/getLogAssetTech','AssetController@getLogAssetTech');

	//MSP inventory gudang
	Route::get('/inventory/msp','WarehouseController@inventory_msp');
	Route::post('/inventory/store/msp','WarehouseController@inventory_store_msp');
	Route::get('/detail_inventory_msp/{id_barang}','WarehouseController@Detail_inventory_msp');
	Route::post('/inventory/detail/store/msp','WarehouseController@inventory_detail_store_msp');
	Route::post('/inventory/msp/update','WarehouseController@inventory_msp_update');
	Route::post('/store/msp/serial_number','WarehouseController@update_serial_number_msp');

	//MSP DO
	Route::get('/inventory/do/msp','WarehouseProjectController@inventory_index_msp');
	Route::get('/dropdownQty','WarehouseProjectController@getQtyMSP');
	Route::post('/inventory/store/do/msp','WarehouseProjectController@store_delivery_msp');
	Route::post('/store/product/do/msp','WarehouseProjectController@store_product_do_msp');
	Route::get('/detail/do/msp/{id_transaction}','WarehouseProjectController@Detail_do_msp');

	//DO number
	Route::get('/do_number_msp','DONumberController@index_msp');
	Route::get('/downloadPdfDO/{id_transaction}', 'WarehouseProjectController@downloadPdfDO');
	Route::get('/downloadPdfDOSIP/{id_inventory_project}', 'WarehouseProjectController@downloadPdfDOSIP');

	Route::get('/po_asset', 'POAssetController@index');
	Route::get('/downloadPdfPO/{id_po_asset}', 'POAssetController@downloadPDF2');
	Route::post('/update_term_po', 'POAssetController@update');

	//Asset MSP
	Route::get('/asset_msp', 'WarehouseAssetController@index_msp');
	Route::post('/update_peminjaman_asset_msp', 'WarehouseAssetController@peminjaman_msp');
	Route::get('/detail_asset_msp/{id_barang}','WarehouseAssetController@detail_asset_msp');
	Route::post('/accept_pinjam_warehouse_msp', 'WarehouseAssetController@accept_pinjam_msp');
	Route::post('/reject_pinjam_warehouse_msp', 'WarehouseAssetController@reject_msp');
	Route::post('/kembali_pinjam_warehouse_msp', 'WarehouseAssetController@kembali_msp');

	//PO MSP
	Route::get('/po_msp', 'PONumberMSPController@index');
	Route::post('/store_po_msp', 'PONumberMSPController@store');
	Route::post('/update_po_msp','PONumberMSPController@update');
	Route::get('/delete_po_msp/{id_po}','PONumberMSPController@destroy');
	Route::get('/downloadExcelPOMSP', 'PONumberMSPController@downloadExcelPO');
	Route::get('/po_asset_msp', 'POAssetMSPController@index');
	Route::post('/update_term_po_msp', 'POAssetMSPController@update');
	Route::get('/downloadPdfPO2/{id_po_asset}', 'POAssetMSPController@downloadPDF2');

	//PR Asset MSP
	Route::get('/pr_asset_msp','PAMMSPController@index');
	Route::post('/store_pr_asset_msp', 'PAMMSPController@tambah');
	Route::post('/store_produk_msp', 'PAMMSPController@store_produk');
	Route::get('/delete_produk_msp','PAMMSPController@delete_produk');
	Route::post('/update_produk_msp','PAMMSPController@update_produk');
	Route::get('/delete_pr_asset_msp/{id}', 'PAMMSPController@destroy');
	Route::post('/edit_pr_asset_msp', 'PAMMSPController@update');
	Route::get('/detail_pam_msp/{id_pam}', 'PAMMSPController@detail_pam');
	Route::post('/assign_to_fnc_pr_asset_msp','PAMMSPController@assign_to_fnc');
	Route::post('/assign_to_adm_pr_asset_msp','PAMMSPController@assign_to_adm');
	Route::get('/downloadPdfPRMSP/{id_pam}', 'PAMMSPController@downloadPDF2');
	Route::post('/tambah_return_fnc_pr_asset_msp', 'PAMMSPController@tambah_return_fnc');

	//Bank Garansi
	Route::get('/bank_garansi', 'BGaransiController@index');
	Route::get('/add_bgaransi', 'BGaransiController@add_bgaransi');
	Route::post('/store_bgaransi', 'BGaransiController@store');
	Route::get('/edit_bg/{id_bank_garansi}', 'BGaransiController@edit_bg');
	Route::post('/update_bg', 'BGaransiController@update');
	Route::get('/downloadpdfbg/{id_bank_garansi}', 'BGaransiController@pdf');
	Route::post('/update_status', 'BGaransiController@update_status');
	Route::get('/downloadpdfsk/{id_bank_garansi}', 'BGaransiController@downloadpdfsk');
	Route::post('/accept_status', 'BGaransiController@accept_status');

	// Asset Technical
	Route::get('/getKategori','AssetController@getKategori');
	Route::get('/getAssetTech','AssetController@getAssetTech');
	Route::get('/getAssetTransactionModerator','AssetController@getAssetTransactionModerator');
	Route::get('/getAssetTransaction','AssetController@getAssetTransaction');

	Route::get('/asset_pinjam', 'AssetController@index');
	Route::get('/edit_pinjam', 'AssetController@edit');
	Route::post('/store_asset', 'AssetController@store');
	Route::post('/update_asset', 'AssetController@update');
	Route::post('/accept_pinjam', 'AssetController@accept');
	Route::post('/reject_pinjam', 'AssetController@reject');
	Route::post('/ambil_pinjam', 'AssetController@ambil');
	Route::post('/kembali_pinjam', 'AssetController@kembali');
	Route::get('/detail_asset_peminjaman/{id_barang}','AssetController@detail_asset_peminjaman');
	Route::get('/delete_asset/{id_barang}', 'AssetController@destroy');
	Route::get('/store_kategori_asset', 'AssetController@store_kategori');
	Route::get('/getidkategori', 'AssetController@getdropdownkategori');
	Route::get('/dropdownSerialNumberAsset', 'AssetController@getdropdownsn');
	Route::get('/dropdownid_barang', 'AssetController@getid_barang');
	Route::get('/dropdownid_barang_reject', 'AssetController@getid_barang_reject');
	Route::get('/dropdownsn', 'AssetController@getsn');
	Route::get('/getdetailAsset','AssetController@getdetailAsset');
	Route::get('/getdetailAssetPeminjaman','AssetController@getdetailAssetPeminjaman');
	Route::get('/getAsset','AssetController@getAsset');
	Route::get('/getidbarangaccept', 'AssetController@getid_barang_accept');
	Route::post('/asset_pinjam/updateKategori', 'AssetController@updateKategori');
	Route::get('/asset_pinjam/getKategoriById', 'AssetController@getKategoriById');

	//App Incident
	Route::get('/app_incident', 'AppIncidentController@index');
	Route::post('/store_app_incident', 'AppIncidentController@store');
	Route::post('/update_status_app_inc', 'AppIncidentController@update_status');
	Route::post('/update_app_incident', 'AppIncidentController@update_app_incident');

	// Backup Route Demo for New Templating
	Route::get('/s4l3s1324','SalesDemoController@index')->middleware('ManagerStaffMiddleware');
	Route::get('/s4l3s1324_detail/{lead_id}','SalesDemoController@detail_sales');
	Route::get('/s4l3s1324_pr','SalesDemoController@admin_pr');
	Route::get('/s4l3s1324_po','SalesDemoController@admin_po');
	Route::get('/s4l3s1324_letter','SalesDemoController@admin_letter');
	Route::get('/s4l3s1324_quote','SalesDemoController@admin_quote');
	Route::get('/s4l3s1324_hr','SalesDemoController@admin_hr');
	Route::get('/s4l3s1324_employees','SalesDemoController@employees');
	Route::get('/s4l3s1324_leaving_permit','SalesDemoController@leaving_permit');
	Route::get('/s4l3s1324_customer','SalesDemoController@customer');
	Route::get('/s4l3s1324_idpro','SalesDemoController@idpro');
	Route::get('/s4l3s1324_partnership','SalesDemoController@partnership');
	Route::get('/s4l3s1324_sho','SalesDemoController@sho');
	Route::get('/s4l3s1324_detail_sho/{id_sho}','SalesDemoController@detail_sho');
	Route::get('/s4l3s1324_report_range','SalesDemoController@report_range');
	Route::get('/s4l3s1324_claim','SalesDemoController@claim');
	Route::get('/s4l3s1324_detail_claim/{no}', 'SalesDemoController@detail_claim');
	Route::get('/s4l3s1324_pam', 'SalesDemoController@pam');
	Route::get('/s4l3s1324_detail_pam/{id_pam}', 'SalesDemoController@detail_pam');
	Route::get('/s4l3s1324_profile', 'SalesDemoController@profile');
	Route::get('/s4l3s1324_dashboard', 'SalesDemoController@dashboard');
	Route::get('/s4l3s1324_view_lead', 'SalesDemoController@view_lead');
	Route::get('/s4l3s1324_view_open', 'SalesDemoController@view_open');
	Route::get('/s4l3s1324_view_lose', 'SalesDemoController@view_lose');
	Route::get('/s4l3s1324_view_win', 'SalesDemoController@view_win');
	Route::get('/s4l3s1324_claim_pending', 'SalesDemoController@claim_pending');
	Route::get('/s4l3s1324_claim_transfer', 'SalesDemoController@claim_transfer');
	Route::get('/s4l3s1324_claim_admin', 'SalesDemoController@claim_admin');

	// Implementation
	Route::get('/implementation', 'ImplementationController@index');
	Route::post('/implementation/store', 'ImplementationController@store');
	Route::post('/implementation/update_project', 'ImplementationController@update_project');
	Route::get('/project_delete/{id}', 'ImplementationController@project_delete');
	Route::get('/implementation/{id}', 'ImplementationController@detail');
	Route::post('/implementation/engineer_problem', 'ImplementationController@engineer_problem');
	Route::post('/implementation/engineer_progress', 'ImplementationController@engineer_progress');
	Route::post('/implementation/engineer_progress_edit', 'ImplementationController@engineer_progress_edit');
	Route::post('/implementation/engineer_problem_edit', 'ImplementationController@engineer_problem_edit');
	Route::get('/progress_delete/{id}', 'ImplementationController@progress_delete');
	Route::get('/problem_delete/{id}', 'ImplementationController@problem_delete');
	Route::post('/implementation/edit_phase', 'ImplementationController@edit_phase');
	Route::post('/implementation/update_phase', 'ImplementationController@update_phase');
	Route::post('/implementation/update_engineer', 'ImplementationController@update_engineer');
	Route::post('/implementation/update_leader', 'ImplementationController@update_leader');
	Route::get('/engineer_delete/{id}', 'ImplementationController@engineer_delete');
	Route::get('imp/getprogress','ImplementationController@get_data_progress');
	Route::get('imp/getproblem','ImplementationController@get_data_problem');

	//asset HR

	Route::get('/asset_hr', 'AssetHRController@index');
	Route::post('/store_asset_hr', 'AssetHRController@store');
	Route::post('/peminjaman_hr', 'AssetHRController@peminjaman');
	Route::post('/requestPeminjaman','AssetHRController@requestPeminjaman');
	Route::post('/penghapusan_hr', 'AssetHRController@penghapusan');
	Route::post('/accept_pinjam_hr', 'AssetHRController@accept_pinjam');
	Route::post('/reject_pinjam_hr', 'AssetHRController@reject_pinjam');
	Route::post('/kembali_pinjam_hr', 'AssetHRController@kembali');
	Route::get('/detail_peminjaman_hr/{id_barang}', 'AssetHRController@detail_asset');
	Route::get('/get_detail_hr', 'AssetHRController@getdetail');
	Route::get('/get_detail_hr2', 'AssetHRController@getdetail2');
	Route::get('/getPengembalian', 'AssetHRController@getPengembalian');
	Route::get('/getEditAsset', 'AssetHRController@getEditAsset');
	Route::post('/edit_asset', 'AssetHRController@edit_asset');
	Route::get('/exportExcelAsset', 'AssetHRController@export');
	Route::get('/getAssetCategoriHR','AssetHRController@getCategory');
	Route::get('/getCategoryPinjam','AssetHRController@getCategoryPinjam');
	Route::post('/store_kategori_asset','AssetHRController@store_kategori');
	Route::get('/getDetailBorrowed','AssetHRController@getDetailBorrowed');
	Route::get('/acceptPeminjaman','AssetHRController@acceptPeminjaman');
	Route::get('/storeRequestAsset','AssetHRController@storeRequestAsset');
	Route::get('/acceptNewAsset','AssetHRController@acceptNewAsset');
	Route::post('/createNewAsset','AssetHRController@createNewAsset');
	Route::get('/getRequestAssetBy','AssetHRController@getRequestAssetBy');
	Route::get('/AddNoteReq','AssetHRController@AddNoteReq');
	Route::get('/batalkanReq','AssetHRController@batalkanReq');
	Route::get('/getListAsset','AssetHRController@getListAsset');
	Route::post('/importAssetHR','AssetHRController@import');


	Route::get('asset_atk', 'AssetAtkController@index');
	Route::post('asset_atk/store_asset_atk', 'AssetAtkController@store');
	Route::get('asset_atk/get_qty_atk','AssetAtkController@getqtyatk');
	Route::post('asset_atk/request_atk', 'AssetAtkController@request_atk');
	Route::get('asset_atk/accept_request', 'AssetAtkController@accept_request');
	Route::post('asset_atk/edit_atk', 'AssetAtkController@edit_atk');
	Route::get('asset_atk/reject_request', 'AssetAtkController@reject_request');
	Route::get('asset_atk/detail_asset_atk/{id_barang}','AssetAtkController@detail');
	Route::post('asset_atk/update_stok', 'AssetAtkController@update_stok');
	Route::post('asset_atk/done_request_pr', 'AssetAtkController@done_request_pr');
	Route::get('asset_atk/getAssetAtk', 'AssetAtkController@getAtk');
	Route::post('asset_atk/store_request_atk', 'AssetAtkController@store_request_atk');
	Route::get('asset_atk/accept_request_atk', 'AssetAtkController@accept_request_atk');
	Route::post('asset_atk/done_request_atk', 'AssetAtkController@request_done');
	Route::get('asset_atk/reject_request_atk', 'AssetAtkController@reject_request_atk');
	Route::get('asset_atk/detail_produk_request', 'AssetAtkController@detail_produk_request');
	Route::get('asset_atk/getSummaryAtk', 'AssetAtkController@getSummaryAtk');
	Route::get('asset_atk/getSummaryQty', 'AssetAtkController@getSummaryQty');
	Route::get('asset_atk/getSaldoAtk', 'AssetAtkController@getSaldoAtk');
	Route::get('asset_atk/getMostRequest', 'AssetAtkController@getMostRequest');
	Route::get('asset_atk/reportExcel', 'AssetAtkController@reportExcel');

	Route::get('asset_logistik', 'AssetLogistikController@index');
	Route::post('asset_logistik/store_asset_logistik', 'AssetLogistikController@store');
	Route::get('asset_logistik/get_qty_logistik','AssetLogistikController@getqtylogistik');
	Route::post('asset_logistik/request_logistik', 'AssetLogistikController@request_logistik');
	Route::get('asset_logistik/accept_request', 'AssetLogistikController@accept_request');
	Route::post('asset_logistik/edit_logistik', 'AssetLogistikController@edit_logistik');
	Route::get('asset_logistik/reject_request', 'AssetLogistikController@reject_request');
	Route::get('asset_logistik/detail_asset_logistik/{id_barang}','AssetLogistikController@detail');
	Route::post('asset_logistik/update_stok', 'AssetLogistikController@update_stok');
	Route::post('asset_logistik/done_request_pr', 'AssetLogistikController@done_request_pr');
	Route::get('asset_logistik/getAssetLogistik', 'AssetLogistikController@getLogistik');
	Route::post('asset_logistik/store_request_logistik', 'AssetLogistikController@store_request_logistik');
	Route::get('asset_logistik/accept_request_logistik', 'AssetLogistikController@accept_request_logistik');
	Route::post('asset_logistik/done_request_logistik', 'AssetLogistikController@request_done');
	Route::get('asset_logistik/reject_request_logistik', 'AssetLogistikController@reject_request_logistik');
	Route::get('asset_logistik/detail_produk_request', 'AssetLogistikController@detail_produk_request');
	Route::get('asset_logistik/getSummaryLogistik', 'AssetLogistikController@getSummaryLogistik');
	Route::get('asset_logistik/getSummaryQty', 'AssetLogistikController@getSummaryQty');
	Route::get('asset_logistik/getSaldoLogistik', 'AssetLogistikController@getSaldoLogistik');
	Route::get('asset_logistik/getMostRequest', 'AssetLogistikController@getMostRequest');
	Route::get('asset_logistik/reportExcel', 'AssetLogistikController@reportExcel');

	//PMO
	Route::get('PMO/detail/{lead_id}','PMOController@detail');
	Route::get('PMO/index','PMOController@index');
	Route::post('store_phase','PMOController@store_stage');
	Route::post('add_progress','PMOController@add_progress');
	Route::get('progress/getprogress','PMOController@geteditprogress');
	Route::post('edit/phase','PMOController@edit_phase');
	Route::post('update/phase','PMOController@update_phase');
	Route::post('progress/edit','PMOController@progress_edit');
	Route::post('update/leader','PMOController@update_leader');
	Route::post('add/problem','PMOController@add_problem');

	//PM
	Route::get('/PMO/project','PMProjectController@pmoPmIndex');
	Route::get('/PMO/project/detail/{id_pmo}','PMProjectController@pmoPmDetail');
	Route::get('/PMO/dashboard','PMProjectController@pmoPmDashboard');
	Route::get('/PMO/getListDataProject','PMProjectController@getListDataProject');
	Route::get('/PMO/getListDataPid','PMProjectController@getListDataPid');
	Route::get('/PMO/getPMStaff','PMProjectController@getPMStaff');
	Route::get('/PMO/getPCStaff','PMProjectController@getPCStaff');
	Route::post('/PMO/assignProject','PMProjectController@assignProject');
	Route::get('/PMO/getListforProjectCharterById','PMProjectController@getListforProjectCharterById');
	Route::get('/PMO/getDefaultTask','PMProjectController@getDefaultTask');
	Route::get('/PMO/showProjectCharter','PMProjectController@showProjectCharter');
	Route::get('/PMO/getUser','PMProjectController@getUser');
	Route::post('/PMO/storeProjectCharter','PMProjectController@storeProjectCharter');
	Route::post('/PMO/updateProjectCharter','PMProjectController@updateProjectCharter');
	Route::get('/PMO/getSignProjectCharter','PMProjectController@getSignProjectCharter');
	Route::post('/PMO/approveProjectCharter','PMProjectController@approveProjectCharter');
	Route::post('/PMO/rejectProjectCharter','PMProjectController@rejectProjectCharter');
	Route::post('/PMO/rejectFinalReport','PMProjectController@rejectFinalReport');
	Route::post('/PMO/storeApproveFinalReport','PMProjectController@storeApproveFinalReport');
	Route::post('/PMO/updateInternalStakholder','PMProjectController@updateInternalStakholder');
	Route::post('/PMO/updateIdentifiedRisk','PMProjectController@updateIdentifiedRisk');
	Route::get('/PMO/getIssue','PMProjectController@getIssue');
	Route::get('/PMO/getRisk','PMProjectController@getRisk');
	Route::get('/PMO/getDetailIssue','PMProjectController@getDetailIssue');
	Route::get('/PMO/getDetailRisk','PMProjectController@getDetailRisk');
	Route::get('/PMO/getMilestone','PMProjectController@getMilestone');
	Route::get('/PMO/getStageWeekly','PMProjectController@getStageWeekly');
	Route::get('/PMO/getGantt', 'PMProjectController@getGantt');
	Route::get('/PMO/getMilestoneById','PMProjectController@getMilestoneById');
	Route::post('/PMO/postIssueProblems','PMProjectController@postIssueProblems');
	Route::post('/PMO/postRisk','PMProjectController@postRisk');
	Route::get('/PMO/getShowDocument','PMProjectController@getShowDocument');
	Route::post('/PMO/storeMilestone','PMProjectController@storeMilestone');
	Route::post('/PMO/storeCustomMilestone','PMProjectController@storeCustomMilestone');
	Route::post('/PMO/storeWeeklyReport','PMProjectController@storeWeeklyReport');
	Route::post('/PMO/storeFinalReport','PMProjectController@storeFinalReport');
	Route::post('/PMO/storeScheduleRemarksFinalReport','PMProjectController@storeScheduleRemarksFinalReport');
	Route::post('/PMO/updateCustomerInfoProjectCharter','PMProjectController@updateCustomerInfoProjectCharter');
	Route::post('/PMO/updateProjectInformationProjectCharter','PMProjectController@updateProjectInformationProjectCharter');
	Route::post('/PMO/updateStatusTask','PMProjectController@updateStatusTask');
	Route::get('/PMO/getProgressBar','PMProjectController@getProgressBar');
	Route::post('/PMO/storeCustomerInfoProjectCharter','PMProjectController@storeCustomerInfoProjectCharter');
	Route::get('/PMO/mailPMO','PMProjectController@mailPMO');
	Route::get('/PMO/getFinalReportById','PMProjectController@getFinalReportById');
	Route::get('/PMO/getDeliverableDocument','PMProjectController@getDeliverableDocument');
	Route::post('/PMO/storeDocument','PMProjectController@storeDocument');
	Route::get('/PMO/getProjectDocument','PMProjectController@getProjectDocument');
	Route::post('/PMO/sendMailCss','PMProjectController@sendMailCss');
	Route::get('/PMO/uploadPdfPC','PMProjectController@uploadPdfPC');
	Route::get('/PMO/uploadPdfFinalReport','PMProjectController@uploadPdfFinalReport');
	Route::get('/PMO/uploadPdfWeekly','PMProjectController@uploadPdfWeekly');
	Route::get('/PMO/getCountDashboard','PMProjectController@getCountDashboard');
	Route::get('/PMO/getTotalProjectType','PMProjectController@getTotalProjectType');
	Route::get('/PMO/getMarketSegment','PMProjectController@getMarketSegment');
	Route::get('/PMO/getProjectValue','PMProjectController@getProjectValue');
	Route::get('/PMO/getProjectStatus','PMProjectController@getProjectStatus');
	Route::get('/PMO/getNominalByPeople','PMProjectController@getNominalByPeople');
	Route::get('/PMO/getProjectPhase','PMProjectController@getProjectPhase');
	Route::get('/PMO/getHandoverProject','PMProjectController@getHandoverProject');
	Route::get('/PMO/getTotalProject','PMProjectController@getTotalProject');
	Route::get('/PMO/getProjectHealth','PMProjectController@getProjectHealth');
	Route::post('/PMO/deleteAssign','PMProjectController@deleteAssign');
	Route::post('/PMO/deleteDoc','PMProjectController@deleteDoc');
	Route::get('/PMO/exportRiskExcel','PMProjectController@exportRiskExcel');
	Route::get('/PMO/exportIssueExcel','PMProjectController@exportIssueExcel');
	Route::get('/PMO/getAllActivity','PMProjectController@getAllActivity');

	Route::get('/PMO/getPhase','PMProjectController@getPhase');

	

	// Route::get('/PMO/getDeliverableDocument','TestController@getDeliverableDocument');


	//Gantt
	Route::post('/PMO/createBaseline','GanttTaskPMOController@createBaseline');
	


	//getLeadByCustpmer
	Route::get('/getLeadByCompany','SalesController@getLeadByCompany');
	//presence
	Route::get('/presence', 'PresenceController@index');
	Route::get('/presence/getPresenceParameter','PresenceController@getPresenceParameter');
	Route::post('/presence/checkIn', 'PresenceController@checkIn');
	Route::post('/presence/checkOut', 'PresenceController@checkOut');
	Route::get('/presence/history/personal', 'PresenceController@personalHistory');
	Route::get('/presence/history/team', 'PresenceController@teamHistory');
	Route::get('/presence/report', 'PresenceController@presenceReport');
	Route::get('/presence/report/getData', 'PresenceController@getPresenceReportData');
	Route::get('/presence/report/getExportRerport', 'PresenceController@getExportReport');
	Route::get('/presence/report/getDataReportPresence', 'PresenceController@getDataReportPresence');
	Route::get('/presence/report/getFilterReport', 'PresenceController@getFilterReport');
	Route::get('/presence/report/getReportPresenceDummy', 'PresenceController@getReportPresenceDummy');
	
	Route::get('/presence/setting', 'PresenceController@presenceSetting');
	Route::get('/presence/setting/getListUser', 'PresenceController@presenceSettingGetListUser');
	Route::get('/presence/setting/showSchedule', 'PresenceController@presenceSettingShowSchedule');
	Route::get('/presence/setting/showLocation', 'PresenceController@presenceSettingShowLocationExisting');
	Route::get('/presence/setting/showAllLocation', 'PresenceController@presenceSettingShowLocationUser');
	Route::post('/presence/setting/setSchedule', 'PresenceController@presenceSettingSetSchedule');
	Route::post('/presence/setting/setLocation', 'PresenceController@presenceSettingSetLocation');
	Route::post('/presence/setting/addLocation', 'PresenceController@presenceSettingAddLocation');
	Route::get('/presence/setting/showLocationAll', 'PresenceController@presenceSettingShowAllLocation');
	Route::get('/presence/getUser','PresenceController@getAllUser');


	Route::get('/presence/shifting', 'PresenceController@presenceShifting');
	Route::get('/presence/shifting/getProject', 'PresenceController@shiftingGetProject');
	Route::get('/presence/shifting/getOption', 'PresenceController@shiftingGetOption');
	Route::get('/presence/shifting/getUsers', 'PresenceController@shiftingGetUsers');
	Route::get('/presence/shifting/getThisMonth', 'PresenceController@getScheduleThisMonth');
	Route::get('/presence/shifting/getSummaryThisMonth', 'PresenceController@getSummaryThisMonth');
	Route::get('/presence/shifting/getThisProject', 'PresenceController@getScheduleThisProject');
	Route::get('/presence/shifting/getThisUser', 'PresenceController@getScheduleThisUser');
	Route::get('/presence/shifting/createSchedule', 'PresenceController@createSchedule');
	Route::get('/presence/shifting/deleteSchedule', 'PresenceController@deleteSchedule');
	Route::get('/presence/shifting/addProject', 'PresenceController@addProject');


	Route::post('/presence/shifting/modifyUserShifting', 'PresenceController@modifyUserShifting');
	Route::get('/presence/shifting/modifyOptionShifting', 'PresenceController@modifyOptionShifting');
	Route::get('/presence/shifting/getOptionGrouped', 'PresenceController@getOptionGrouped');

	Route::get('/presence/shifting/showLogActivity', 'PresenceController@getLogActivityShifting');
	Route::get('/presence/shifting/getReportShifting', 'PresenceController@getReportShifting');

	Route::get('/presence/history/personalMsp', 'PresenceController@personalHistoryMsp');
	Route::get('/presence/getLocationNameFromLatLng', 'PresenceController@getLocationNameFromLatLng');

	// Invoice
	Route::get('/invoice', 'InvoiceController@index');
	Route::get('/invoice/getNoPo', 'InvoiceController@getNoPo');
	Route::post('/invoice/store', 'InvoiceController@store');
	Route::get('/invoice/getData', 'InvoiceController@getData');
	Route::post('/invoice/update_invoice', 'InvoiceController@update_invoice');
	Route::get('/invoice/getInvoiceEdit', 'InvoiceController@getInvoiceEdit');
	Route::get('/invoice/getFilterYear', 'InvoiceController@getFilterYear');
	Route::get('downloadExcelInvoice','InvoiceController@downloadExcel');

	// Ticketing
	Route::get('/ticketing','TicketingController@index');
	Route::get('/ticketing/getDashboard','TicketingController@getDashboard');

	Route::get('/ticketing/create/getParameter','TicketingController@getCreateParameter');
	Route::get('/ticketing/create/getReserveIdTicket','TicketingController@getReserveIdTicket');
	Route::get('/ticketing/create/setReserveIdTicket','TicketingController@setReserveIdTicket');
	Route::get('/ticketing/create/putReserveIdTicket','TicketingController@putReserveIdTicket');
	Route::get('/ticketing/create/getAtmId','TicketingController@getAtmId');
	Route::get('/ticketing/create/getAbsenId','TicketingController@getAbsenId');
	Route::get('/ticketing/create/getAtmDetail','TicketingController@getAtmDetail');
	Route::get('/ticketing/create/getAbsenDetail','TicketingController@getAbsenDetail');
	Route::get('/ticketing/create/getAtmPeripheralDetail','TicketingController@getAtmPeripheralDetail');
	Route::get('/ticketing/create/getSwitchId','TicketingController@getSwitchId');
	Route::get('/ticketing/create/getSwitchDetail','TicketingController@getSwitchDetail');

	Route::get('/ticketing/mail/getEmailData','TicketingController@getEmailData');
	Route::get('/ticketing/mail/getEmailTemplate','TicketingController@getEmailTemplate');
	Route::get('/ticketing/mail/getOpenMailTemplate','TicketingController@getOpenMailTemplate');
	Route::get('/ticketing/mail/sendEmailOpen','TicketingController@sendEmailOpen');
	Route::post('/ticketing/mail/storeAddMail', 'TicketingController@storeAddMail');
	Route::get('/ticketing/mail/getSettingEmail', 'TicketingController@getSettingEmail');

	Route::get('/ticketing/getPerformanceAll','TicketingController@getPerformanceAll');
	Route::get('/ticketing/getPerformanceByClient','TicketingController@getPerformanceByClient');
	Route::get('/ticketing/getPerformanceByTicket','TicketingController@getPerformanceByTicket');
	Route::get('/ticketing/getPerformanceBySeverity','TicketingController@getPerformanceBySeverity');
	Route::get('/ticketing/getPerformanceByFilter','TicketingController@getPerformanceByFilter');

	Route::get('/ticketing/setUpdateTicket','TicketingController@setUpdateTicket');
	Route::get('/ticketing/mail/getOnProgressMailTemplate','TicketingController@getOnProgressMailTemplate');

	Route::get('/ticketing/mail/getCancelMailTemplate','TicketingController@getCancelMailTemplate');
	Route::get('/ticketing/mail/sendEmailCancel','TicketingController@sendEmailCancel');

	Route::get('/ticketing/getPendingTicketData','TicketingController@getPendingTicketData');
	Route::get('/ticketing/mail/getPendingMailTemplate','TicketingController@getPendingMailTemplate');
	Route::get('/ticketing/setUpdateTicketPending','TicketingController@setUpdateTicketPending');
	Route::get('/ticketing/mail/sendEmailPending','TicketingController@sendEmailPending');

	Route::get('/ticketing/mail/getCloseMailTemplate','TicketingController@getCloseMailTemplate');
	Route::get('/ticketing/mail/sendEmailClose','TicketingController@sendEmailClose');

	Route::get('/ticketing/mail/getEscalateMailTemplate', 'TicketingController@getEscalateMailTemplate');
	Route::get('/ticketing/mail/sendEmailEscalate', 'TicketingController@sendEmailEscalate');
	Route::get('/ticketing/saveEscalate', 'TicketingController@saveEscalate');

	Route::get('/ticketing/reOpenTicket','TicketingController@reOpenTicket');

	Route::get('/ticketing/setting/getSettingClient','TicketingController@getSettingClient');
	Route::post('/ticketing/setting/setSettingClient' , 'TicketingController@setSettingClient');

	Route::get('/ticketing/setting/getAllAtm', 'TicketingController@getAllAtmSetting');
	Route::get('/ticketing/setting/getParameterAddAtm','TicketingController@getParameterAddAtm');
	Route::get('/ticketing/setting/newAtm','TicketingController@newAtm');
	Route::get('/ticketing/setting/newAtmPeripheral','TicketingController@newAtmPeripheral');
	Route::get('/ticketing/setting/setAtm','TicketingController@setAtm');
	Route::get('/ticketing/setting/deleteAtm','TicketingController@deleteAtm');
	Route::get('/ticketing/setting/editAtmPeripheral','TicketingController@editAtmPeripheral');
	Route::get('/ticketing/setting/deleteAtmPeripheral','TicketingController@deleteAtmPeripheral');

	Route::get('/ticketing/setting/getAllAbsen', 'TicketingController@getAllAbsenSetting');
	Route::get('/ticketing/setting/getDetailAbsen','TicketingController@getDetailAbsen');
	Route::get('/ticketing/setting/newAbsen','TicketingController@newAbsen');
	Route::get('/ticketing/setting/setAbsen','TicketingController@setAbsen');
	Route::get('/ticketing/setting/deleteAbsen','TicketingController@deleteAbsen');
	Route::get('/ticketing/setting/getDetailAtm','TicketingController@getDetailAtm');

	Route::get('/ticketing/setting/getAllSwitch', 'TicketingController@getAllSwitchSetting');
	Route::get('/ticketing/setting/getDetailSwitch','TicketingController@getDetailSwitch');
	Route::get('/ticketing/setting/newSwitch','TicketingController@newSwitch');
	Route::get('/ticketing/setting/setSwitch','TicketingController@setSwitch');
	Route::get('/ticketing/setting/deleteSwitch','TicketingController@deleteSwitch');

	Route::get('/ticketing/report/getParameter','TicketingController@getReportParameter');
	Route::get('/ticketing/report/make','TicketingController@makeReportTicket');
	Route::get('/ticketing/report/download','TicketingController@downloadReportTicket');
	Route::get('/ticketing/report/new','TicketingController@getReportNew');
	Route::get('/ticketing/report/newDeny','TicketingController@getReportNewDeny');
	Route::get('/changeNominal/testRequestChange','TestController@testRequestChange');

	Route::get('/report_ticketing','TicketingController@getReportTicket');

	Route::get('/requestChange','RequestChangeController@index');

	Route::get('sbe_index','SBEController@sbe_index');
	Route::get('sbe_detail/{id}','SBEController@sbe_detail');
	Route::get('sbe_create/','SBEController@sbe_detail');
	Route::get('setting','SBEController@setting_sbe');
	Route::get('/sbe/getLead','SBEController@getLead');
	Route::post('/sbe/createConfig','SBEController@createConfig');
	Route::post('/sbe/storeDetailItem','SBEController@storeDetailItem');
	Route::post('/sbe/updateDetailItem','SBEController@updateDetailItem');
	Route::get('/sbe/getDetailItem','SBEController@getDetailItem');
	Route::get('/sbe/getDropdownDetailItem','SBEController@getDropdownDetailItem');
	Route::get('/sbe/getDataSbe','SBEController@getDataSbe');
	Route::get('/sbe/getSoWbyLeadID','SBEController@getSoWbyLeadID');
	Route::get('/sbe/getActivity','SBEController@getActivity');
	Route::post('/sbe/storeNotes','SBEController@storeNotes');
	Route::get('/sbe/getVersionConfig','SBEController@getVersionConfig');
	Route::get('/sbe/getDetailConfig','SBEController@getDetailConfig');
	Route::post('/sbe/updateConfig','SBEController@updateConfig');
	Route::post('/sbe/updateVersion','SBEController@updateVersion');
	Route::get('/sbe/getConfigTemporary','SBEController@getConfigTemporary');
	Route::get('/sbe/getConfigChoosed','SBEController@getConfigChoosed');
	Route::post('/sbe/resetVersion','SBEController@resetVersion');
	Route::post('/sbe/deleteDetailItem','SBEController@deleteDetailItem');


	// Route::get('testDataTable','TestController@testDataTable');
	Route::get('testFullCalendar','TestController@indexCalendar');
	Route::get('getListCalendar','TestController@getListCalendar');
	// Route::get('getListCalendarEvent','TestController@getListCalendarEvent');

	Route::get('timesheet/timesheet','TimesheetController@timesheet');
	Route::get('timesheet/dashboard','TimesheetController@timesheet_dashboard');
	Route::get('timesheet/config','TimesheetController@timesheet_config');

	Route::post('timesheet/addConfig','TimesheetController@addConfig');
	Route::get('getListCalendarEvent','TimesheetController@getListCalendarEvent');
	Route::post('timesheet/storePhaseConfig','TimesheetController@storePhaseConfig');
	Route::post('timesheet/storeTaskConfig','TimesheetController@storeTaskConfig');
	Route::post('timesheet/assignPidConfig','TimesheetController@assignPidConfig');
	Route::post('timesheet/storeLockDuration','TimesheetController@storeLockDuration');
	Route::get('timesheet/getAllPid','TimesheetController@getAllPid');
	Route::get('timesheet/getAllTask','TimesheetController@getAllTask');
	Route::get('timesheet/getAllPhase','TimesheetController@getAllPhase');
	Route::get('timesheet/getLockDurationByDivision','TimesheetController@getLockDurationByDivision');
	Route::get('timesheet/getRoles','TimesheetController@getRoles');
	Route::get('timesheet/getAllUser','TimesheetController@getAllUser');
	Route::get('timesheet/getConfigByDivision','TimesheetController@getConfigByDivision');
	Route::get('timesheet/getPidByPic','TimesheetController@getPidByPic');
	Route::get('timesheet/getTaskByDivision','TimesheetController@getTaskByDivision');
	Route::get('timesheet/getPhaseByDivision','TimesheetController@getPhaseByDivision');
	Route::post('timesheet/addTimesheet','TimesheetController@addTimesheet');
	Route::get('timesheet/getLeadId','TimesheetController@getLeadId');
	Route::get('timesheet/getAllPhaseTask','TimesheetController@getAllPhaseTask');
	Route::get('timesheet/getAllActivityByUser','TimesheetController@getAllActivityByUser');
	Route::get('timesheet/getAllAssignPidByDivision','TimesheetController@getAllAssignPidByDivision');
	Route::get('timesheet/getTaskPhaseByDivisionForTable','TimesheetController@getTaskPhaseByDivisionForTable');
	Route::post('timesheet/storePermit','TimesheetController@storePermit');
	Route::get('timesheet/sumPointMandays','TimesheetController@sumPointMandays');
	Route::get('timesheet/getNameByNik','TimesheetController@getNameByNik');
	Route::get('timesheet/getLevelChart','TimesheetController@getLevelChart');
	Route::get('timesheet/getStatusChart','TimesheetController@getStatusChart');
	Route::get('timesheet/getScheduleChart','TimesheetController@getScheduleChart');
	Route::get('timesheet/getTaskChart','TimesheetController@getTaskChart');
	Route::get('timesheet/getPhaseChart','TimesheetController@getPhaseChart');
	Route::get('timesheet/getRemainingChart','TimesheetController@getRemainingChart');
	Route::get('timesheet/getCummulativeMandaysChart','TimesheetController@getCummulativeMandaysChart');
	Route::get('timesheet/getHoliday','TimesheetController@getHoliday');
	Route::get('timesheet/getPercentage','TimesheetController@getPercentage');
	Route::get('timesheet/sumPointSbe','TimesheetController@sumPointSbe');
	Route::get('timesheet/getFilterSumPointMandays','TimesheetController@getFilterSumPointMandays');
	Route::get('timesheet/getFilterCummulativeMandaysChart','TimesheetController@getFilterCummulativeMandaysChart');
	Route::get('timesheet/getFilterRemainingChart','TimesheetController@getFilterRemainingChart');
	Route::get('timesheet/getFilterStatusChart','TimesheetController@getFilterStatusChart');
	Route::get('timesheet/getFilterLevelChart','TimesheetController@getFilterLevelChart');
	Route::get('timesheet/getFilterScheduleChart','TimesheetController@getFilterScheduleChart');
	Route::get('timesheet/getFilterTaskChart','TimesheetController@getFilterTaskChart');
	Route::get('timesheet/getFilterPhaseChart','TimesheetController@getFilterPhaseChart');
	Route::post('timesheet/deleteAllActivity','TimesheetController@deleteAllActivityByDate');
	Route::get('timesheet/deleteTaskPhase','TimesheetController@deleteTaskPhase');
	Route::get('timesheet/exportExcel','TimesheetController@exportExcel');
	Route::post('timesheet/deletePermit','TimesheetController@deletePermit');
	Route::post('timesheet/uploadCSV','TimesheetController@uploadCSV');
	Route::get('timesheet/getListOperation','TimesheetController@getListOperation');
	Route::get('timesheet/getActivitybyDate','TimesheetController@getActivitybyDate');
	Route::get('timesheet/isFillFeeling','TimesheetController@isFillFeeling');
	Route::post('timesheet/storeFeeling','TimesheetController@storeFeeling');
	Route::post('timesheet/deleteActivity','TimesheetController@deleteActivity');
	Route::post('timesheet/updateDateEvent','TimesheetController@updateDateEvent');
	Route::get('timesheet/detailActivitybyPid','TimesheetController@detailActivitybyPid');




	Route::get('/insights','InsightsController@index');
	Route::get('/insights/create','InsightsController@create');
	Route::delete('/insights/delete/{id}','InsightsController@destroy');
	Route::post('/insights/store','InsightsController@store');
	Route::get('/insights/edit/{id}','InsightsController@edit');
	Route::put('/insights/update/{id}','InsightsController@update');	
	Route::post('/ckeditor/upload','InsightsController@ckstore')->name('ckeditor.upload');
	
	Route::get('/solution','SolutionController@index');
	Route::post('/solution/create','SolutionController@store');
	Route::put('/solution/update/{id}','SolutionController@update');
	
	Route::get('/message','QuotationController@index');
	Route::get('/message/detail/{id}','QuotationController@show');
	Route::delete('/message/delete/{id}','QuotationController@destroy');
	Route::post('/message/send','QuotationController@store');
	
	Route::get('/tag', 'TagController@index');
	Route::delete('/tag/delete/{id}', 'TagController@destroy');
	Route::get('/tag/edit/{id}', 'TagController@edit');
	Route::put('/tag/update/{id}', 'TagController@update');
	
	Route::get('/campaign','CampaignController@index');
	Route::get('/campaign/create','CampaignController@create');
	Route::post('/campaign/store','CampaignController@store');
	Route::get('/campaign/edit/{id}','CampaignController@edit');
	Route::put('/campaign/update/{id}','CampaignController@update');
	Route::delete('/campaign/delete/{id}','CampaignController@destroy');
	
	Route::get('/category','CategoryController@index');
	Route::post('/category/store','CategoryController@store');
	Route::get('/category/p/edit/{id}','CategoryController@editP');
	Route::put('/category/p/update/{id}','CategoryController@updateP');
	Route::delete('/category/dp/{id}','CategoryController@destroyP');
	
	Route::get('/project-references', 'ProjectController@index');
	Route::get('/project-references/create', 'ProjectController@create');
	Route::post('/project-references/store', 'ProjectController@store');
	Route::get('/project-references/edit/{id}', 'ProjectController@edit');
	Route::delete('/project-references/destroy/{id}', 'ProjectController@destroy');
	Route::put('/project-references/update/{id}', 'ProjectController@update');
	
	Route::get('/career','CareerController@index');
	Route::get('/career/register','CareerController@register');
	Route::delete('/career/register/{id}','CareerController@register_destroy');
	Route::post('/career/store','CareerController@store');
	Route::delete('/career/d/{id}','CareerController@destroy');
	Route::put('/career/update/{id}','CareerController@update');
	Route::get('/career/edit/{id}','CareerController@edit');
	

});

Route::get('/authentication/{id}','TestController@authentication');
Route::get('/getHoliday','TestController@getWorkdays');

Route::get('/testFullCalendar',function(){
	$controller = new App\Http\Controllers\Controller();
	return view('blankPage')->with(['initView'=>$controller->initMenuBase()]);
});

Route::get('testCheckIn','TestController@checkIn');
Route::post('testCheckIn','TestController@checkIn');
Route::post('testCheckOut','TestController@checkOut');
Route::post('testaddUserShifting','TestController@modifyUserShifting');
Route::get('/mergePdf', 'TestController@mergePdf');

Route::get('testDnsCrypt',function(){
	$client = new GuzzleHttp\Client();
	$res = $client->request('GET', 'https://www.reddit.com/');
});

Route::get('testPdfPR','TestController@testPdfPR');
Route::get('testPdfPRLink','TestController@getLatestPDF');
Route::get('getSignStatusPR','TestController@getSignStatusPR');
Route::get('getLatestPDF','TestController@getLatestPDF');
Route::get('testPdfPRLink','TestController@testPdfPRLink');
Route::get('getListDriveId','TestController@getListDriveId');

Route::post('testUploadDocument','TestController@testUploadDocument');
Route::get('showUploadDocument','TestController@showUploadDocument');
// Route::get('showUploadDocument',function(){
// 	// echo ini_get('post_max_size');
// 	// echo "<pre>";
// 	// print_r(ini_get_all());
// 	// echo "</pre>";
// 	echo phpinfo();
// });


Route::get('sendEmailRejct','TestController@sendEmailRejct');
Route::get('testUploadPDF',function(){
	$pr = new App\Http\Controllers\PrDraftController();
	$pr->uploadPdf(52,"Faiqoh Cantik");
});

Route::get('testCSVUpload','TestController@testCSVUpload');
Route::post('testCSVUploadPost','TestController@testCSVUploadPost');

Route::get('testCSVUploadGetFile','TestController@testCSVUploadGetFile');

Route::get('testGCal','TestController@testGCal');
// Route::get('testSidebar','TestController@testSidebar');


Route::get('downloadSbePdf','TestController@downloadSbePdf');
Route::get('testMailSBE','TestController@testMailSBE');




// Route::get('timesheet/getPhaseByDivisionForTable','TimesheetController@getPhaseByDivisionForTable');