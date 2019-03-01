<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

$route['default_controller'] 	= "home";
$route['404_override'] 			= 'tidakditemukan';
// $route['^(in|en)/reminder'] = "reminder/index/";

// URI for static pages
$route['^(in|en)/pages/committee/(.+)$'] = "committee/index/$2";
$route['^(in|en)/pages/committee/file/(.+)$'] = "committee/file/$2";
$route['^(in|en)/directory/(.+)$'] = "directorys/$2";
$route['^(in|en)/pages/(.+)$'] = "pages/index/$2";
$route['^(in|en)/payment_confirmation/(.+)$'] = "payment_confirmation/index";
// $route['^(in|en)/sitemap'] = "home/sitemap";

$list_old_category_news = array("160915-usiii-2016","amcham","amcham-updates","annual-kalkun-golf-tournament","annual-report","archive","asean","career","commercial-opportunities","committees","community-archive","company-news","consultants","corporate-citizenship","disaster-response-plan","do-not-delete","entry-level","events","executive-exchange-magazine","finance","fs","fe","getting-started","indonesia-information","interviews","investing-in-indonesia","investment-initiative","investment-initiative-report","link-and-ref","manufacturing-industry","media","meetings","membership","membership-privileges-program","monthly-networking-evening","news-bites","newsdigest","newsletter","opinion","past-events-other","pharmaceuticals-lifescience","politics","press","professionals","programs-conferences","promotion","publications","recent-events","recent-monthly-networking-evening","regulations","regulatory","resource","resources","stand-alone","studies","trade-investment","trade-investment-information","uncategorised","updates","vacancies","why-indonesia","yp-social-evening");
foreach ($list_old_category_news as $key => $value) {
	$route['^(in|en)/'.$value.'/(.+)$'] = "old_content/news/$2";
	$route[$value.'/(.+)$'] = "old_content/news/$2";
}

$route['^(in|en)/(.+)$'] = "$2";
$route['^(in|en)$'] = $route['default_controller'];

/* End of file routes.php */
/* Location: ./application/config/routes.php */