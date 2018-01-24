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
$route['manager'] = 'manager';
$route['manager/(.*)'] = 'manager/$1';

$route['(\w{2})/(.*)'] = 'manager/$2';
$route['(\w{2})'] = 'manager';

//$route['(.*)'] = 'manager/$1';

$route['default_controller'] = "Manager";
$route['scaffolding_trigger'] = "";

$route['login'] = 'manager/login';
$route['make_login'] = 'manager/make_login';
$route['logout'] = 'manager/logout';
$route['home'] = 'manager/index';
$route['deleteTransaction'] = 'manager/deleteTransaction';
$route['getTransactionItems'] = 'manager/getTransactionItems';
$route['generateCode'] = 'manager/generateCode';
$route['authorizeCode'] = 'manager/authorizeCode';

$route['settings'] = 'Settings';
$route['saveSettings'] = 'Settings/save';

$route['clients'] = 'Clients';
$route['saveClient'] = 'Clients/save';

$route['users'] = 'Users';
$route['saveUser'] = 'Users/save';

$route['accounts'] = 'Accounts';
$route['saveAccount'] = 'Accounts/save';

$route['properties'] = 'Properties';
$route['saveProperty'] = 'Properties/save';

$route['comentaries'] = 'Comentaries';
$route['saveComentary'] = 'Comentaries/save';

$route['contracts'] = 'Contracts';
$route['saveContract'] = 'Contracts/save';
$route['validateContractParts'] = 'Contracts/validateContractParts';

$route['concepts'] = 'concepts';
$route['saveConcept'] = 'concepts/save';

$route['providers'] = 'Providers';
$route['saveProvider'] = 'Providers/save';
$route['getProviders'] = 'Providers/getProviders';

$route['getEntitiesOnScrollDown'] = 'manager/getEntitiesOnScrollDown';

$route['providersRols'] = 'Providers_rols';
$route['saveProviderRol'] = 'Providers_rols/save';

$route['maintenances'] = 'Maintenances';
$route['saveMaintenance'] = 'Maintenances/save';
$route['maintenanceReport/(:num)'] = 'Maintenances/maintenanceReport/$1';

$route['migrations'] = 'Migrations';
$route['saveMigration'] = 'Migrations/save';

$route['transfers'] = 'Transfers';
$route['transferToSafeBox'] = 'Transfers/transferToSafeBox';
$route['transferToCash'] = 'Transfers/transferToCash';

$route['credits'] = 'Credits';
$route['saveCredit'] = 'Credits/save';
$route['searchCreditConcept'] = 'Credits/searchCreditConcept';
$route['showCreditReport'] = 'Credits/showCreditReport';
$route['showCreditReportList/(:num)'] = "Credits/showCreditReportList/$1";
$route['sendTransactionNotification'] = 'Credits/sendTransactionNotification';

$route['debits'] = 'Debits';
$route['saveDebit'] = 'Debits/save';
$route['showDebitReportList/(:num)'] = 'Debits/showDebitReportList/$1';

$route['buildCashReport'] = 'Reports/buildCashReport';
$route['cashReport'] = 'Reports/cashReport';

$route['buildAccountReport'] = 'Reports/buildAccountReport';
$route['accountReport'] = 'Reports/accountReport';

$route['buildPropietaryRenditionsReport'] = 'Reports/buildPropietaryRenditionsReport';
$route['propietaryRenditionsReport'] = 'Reports/propietaryRenditionsReport';

$route['buildpropietaryLoansReport'] = 'Reports/buildpropietaryLoansReport';
$route['propietaryLoansReport'] = 'Reports/propietaryLoansReport';

$route['buildRenterPaymentHistorialReport'] = 'Reports/buildRenterPaymentHistorialReport';
$route['renterPaymentHistorialReport'] = 'Reports/renterPaymentHistorialReport';

$route['buildRentersInDefaultReport'] = 'Reports/buildRentersInDefaultReport';
$route['rentersInDefaultReport'] = 'Reports/rentersInDefaultReport';

$route['buildPendingRenditionsReport'] = 'Reports/buildPendingRenditionsReport';
$route['pendingRenditionsReport'] = 'Reports/pendingRenditionsReport';

$route['buildRenditionsPercentReport'] = 'Reports/buildRenditionsPercentReport';
$route['renditionsPercentReport'] = 'Reports/renditionsPercentReport';

$route['buildContractsDeclinationReport'] = 'Reports/buildContractsDeclinationReport';
$route['contractsDeclinationReport'] = 'Reports/contractsDeclinationReport';

$route['buildAllConceptsMovementsReport'] = 'Reports/buildAllConceptsMovementsReport';
$route['allConceptsMovementsReport'] = 'Reports/allConceptsMovementsReport';

$route['buildBankTransactionsReport'] = 'Reports/buildBankTransactionsReport';
$route['bankTransactionsReport'] = 'Reports/bankTransactionsReport';

$route['buildGeneralBalanceReport'] = 'Reports/buildGeneralBalanceReport';
$route['generalBalanceReport'] = 'Reports/generalBalanceReport';

$route['buildEndedMaintenancesReport'] = 'Reports/buildEndedMaintenancesReport';
$route['endedMaintenancesReport'] = 'Reports/endedMaintenancesReport';

$route['buildRentersPaymentPercentReport'] = 'Reports/buildRentersPaymentPercentReport';
$route['rentersPaymentPercentReport'] = 'Reports/rentersPaymentPercentReport';

/* End of file routes.php */
/* Location: ./application/config/routes.php */