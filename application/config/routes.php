<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	http://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There area two reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router what URI segments to use if those provided
  | in the URL cannot be matched to a valid route.
  |
 */

$route['default_controller'] = 'manager';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


// cambiar mejorar enrutamiento

$route['manager'] = 'manager';
$route['manager/(.*)'] = 'manager/$1';

$route['(\w{2})/(.*)'] = 'manager/$2';
$route['(\w{2})'] = 'manager';


//$route['(.*)'] = 'manager/$1';

$route['default_controller'] = "manager";
$route['scaffolding_trigger'] = "";

$route['login'] = 'manager/login';
$route['make_login'] = 'manager/make_login';
$route['logout'] = 'manager/logout';
$route['home'] = 'manager/index';
$route['deleteTransaction'] = 'manager/deleteTransaction';
$route['getTransactionItems'] = 'manager/getTransactionItems';
$route['generateCode'] = 'manager/generateCode';
$route['authorizeCode'] = 'manager/authorizeCode';
$route['calculate_begin_cash'] = 'manager/calculateBeginCash';
$route['calculate_progressive_cash'] = 'manager/calculateProgressiveCash';

$route['settings'] = 'settings';
$route['saveSettings'] = 'settings/save';

$route['clients'] = 'clients';
$route['saveClient'] = 'clients/save';

$route['timeline'] = 'timeline';

$route['users'] = 'users';
$route['saveUser'] = 'users/save';

$route['accounts'] = 'accounts';
$route['saveAccount'] = 'accounts/save';

$route['properties'] = 'properties';
$route['saveProperty'] = 'properties/save';

$route['comentaries'] = 'comentaries';
$route['saveComentary'] = 'comentaries/save';

$route['contracts'] = 'contracts';
$route['saveContract'] = 'contracts/save';
$route['validateContractParts'] = 'contracts/validateContractParts';

$route['concepts'] = 'concepts';
$route['saveConcept'] = 'concepts/save';

$route['providers'] = 'providers';
$route['saveProvider'] = 'providers/save';
$route['getProviders'] = 'providers/getProviders';

$route['getEntitiesOnScrollDown'] = 'manager/getEntitiesOnScrollDown';

$route['providersRols'] = 'providers_rols';
$route['saveProviderRol'] = 'providers_rols/save';

$route['maintenances'] = 'maintenances';
$route['saveMaintenance'] = 'maintenances/save';
$route['maintenanceReport/(:num)'] = 'maintenances/maintenanceReport/$1';

$route['inspections'] = 'inspections';
$route['saveInspection'] = 'inspections/save';
$route['inspectionReport/(:num)'] = 'inspections/report/$1';

$route['migrations'] = 'migrations';
$route['saveMigration'] = 'migrations/save';

$route['transfers'] = 'transfers';
$route['transferToSafeBox'] = 'transfers/transferToSafeBox';
$route['transferToCash'] = 'transfers/transferToCash';

$route['credits'] = 'credits';
$route['saveCredit'] = 'credits/save';
$route['searchCreditConcept'] = 'credits/searchCreditConcept';
$route['showCreditReport'] = 'credits/showCreditReport';
$route['showCreditReportList/(:num)'] = "credits/showCreditReportList/$1";
$route['sendTransactionNotification'] = 'credits/sendTransactionNotification';

$route['debits'] = 'debits';
$route['saveDebit'] = 'debits/save';
$route['showDebitReportList/(:num)'] = 'debits/showDebitReportList/$1';
$route['printDebitReceiveList/(:num)'] = 'debits/printDebitReceiveList/$1';
$route['printDebitReceive'] = 'debits/printDebitReceive';

$route['buildCashReport'] = 'reports/buildCashReport';
$route['cashReport'] = 'reports/cashReport';

$route['getRenterDebts'] = 'reports/getRenterDebts';

$route['buildOutmonthTransactionsReport'] = 'reports/buildOutmonthTransactionsReport';
$route['outmonthTransactionsReport'] = 'reports/outmonthTransactionsReport';

$route['deliveryReports'] = 'reports_delivery/index';
$route['emailReceiveRenter'] = 'reports_delivery/emailReceiveRenter';

$route['buildAccountReport'] = 'reports/buildAccountReport';
$route['accountReport'] = 'reports/accountReport';

$route['showRenterDebt/(:num)'] = 'reports/showRenterDebt/$1';

$route['buildPropietaryRenditionsReport'] = 'reports/buildPropietaryRenditionsReport';
$route['propietaryRenditionsReport'] = 'reports/propietaryRenditionsReport';

$route['buildAccountsBalanceReport'] = 'reports/buildAccountsBalanceReport';
$route['accountsBalanceReport'] = 'reports/accountsBalanceReport';

$route['buildAccountsAnualBalanceReport'] = 'reports/buildAccountsAnualBalanceReport';
$route['accountsAnualBalanceReport'] = 'reports/accountsAnualBalanceReport';

$route['buildpropietaryLoansReport'] = 'reports/buildpropietaryLoansReport';
$route['propietaryLoansReport'] = 'reports/propietaryLoansReport';

$route['buildRenterPaymentHistorialReport'] = 'reports/buildRenterPaymentHistorialReport';
$route['renterPaymentHistorialReport'] = 'reports/renterPaymentHistorialReport';

$route['buildRentersInDefaultReport'] = 'reports/buildRentersInDefaultReport';
$route['rentersInDefaultReport'] = 'reports/rentersInDefaultReport';

$route['buildPendingRenditionsReport'] = 'reports/buildPendingRenditionsReport';
$route['pendingRenditionsReport'] = 'reports/pendingRenditionsReport';

$route['buildAccountFlushReport'] = 'reports/buildAccountFlushReport';
$route['accountFlushReport'] = 'reports/accountFlushReport';

$route['buildRenditionsPercentReport'] = 'reports/buildRenditionsPercentReport';
$route['renditionsPercentReport'] = 'reports/renditionsPercentReport';

$route['buildContractsDeclinationReport'] = 'reports/buildContractsDeclinationReport';
$route['contractsDeclinationReport'] = 'reports/contractsDeclinationReport';

$route['buildAllConceptsMovementsReport'] = 'reports/buildAllConceptsMovementsReport';
$route['allConceptsMovementsReport'] = 'reports/allConceptsMovementsReport';

$route['buildBankTransactionsReport'] = 'reports/buildBankTransactionsReport';
$route['bankTransactionsReport'] = 'reports/bankTransactionsReport';

$route['buildGeneralBalanceReport'] = 'reports/buildGeneralBalanceReport';
$route['generalBalanceReport'] = 'reports/generalBalanceReport';

$route['buildEndedMaintenancesReport'] = 'reports/buildEndedMaintenancesReport';
$route['endedMaintenancesReport'] = 'reports/endedMaintenancesReport';

$route['buildRentersPaymentPercentReport'] = 'reports/buildRentersPaymentPercentReport';
$route['rentersPaymentPercentReport'] = 'reports/rentersPaymentPercentReport';

$route['defaultPropietaries'] = 'reports/defaultPropietaries';

$route['buildHonoraryPaymentsReport'] = 'reports/buildHonoraryPaymentsReport';

/* End of file routes.php */
/* Location: ./application/config/routes.php */