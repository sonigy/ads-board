<?php
return array("/view/(\\d+)/"=>array("get"=>array("controller"=>"controllers\\IndexController","action"=>"getAdsView","parameters"=>array(0),"name"=>"IndexController-getAdsView","cache"=>false,"duration"=>false)),"/remove/(\\d+)/"=>array("get"=>array("controller"=>"controllers\\UserDashboardController","action"=>"removeAdvert","parameters"=>array(0),"name"=>"UserDashboardController-removeAdvert","cache"=>false,"duration"=>false)),"/update/(\\d+)/"=>array("get"=>array("controller"=>"controllers\\UserDashboardController","action"=>"updateAdvert","parameters"=>array(0),"name"=>"UserDashboardController-updateAdvert","cache"=>false,"duration"=>false),"post"=>array("controller"=>"controllers\\UserDashboardController","action"=>"updateAdvert","parameters"=>array(0),"name"=>"UserDashboardController-updateAdvert","cache"=>false,"duration"=>false)),"/add/"=>array("get"=>array("controller"=>"controllers\\UserDashboardController","action"=>"addAdvert","parameters"=>array(),"name"=>"UserDashboardController-addAdvert","cache"=>false,"duration"=>false),"post"=>array("controller"=>"controllers\\UserDashboardController","action"=>"addAdvert","parameters"=>array(),"name"=>"UserDashboardController-addAdvert","cache"=>false,"duration"=>false)),"/user-dashboard/"=>array("get"=>array("controller"=>"controllers\\UserDashboardController","action"=>"userDashboard","parameters"=>array(),"name"=>"UserDashboardController-userDashboard","cache"=>false,"duration"=>false)),"/signup/"=>array("controller"=>"controllers\\SignUpUserController","action"=>"signUp","parameters"=>array(),"name"=>"SignUpUserController-signUp","cache"=>false,"duration"=>false),"/(\\d+)/(\\d+)/"=>array("get"=>array("controller"=>"controllers\\IndexController","action"=>"getAdsAll","parameters"=>array(0,1),"name"=>"IndexController-getAdsAll","cache"=>false,"duration"=>false)),"/((?!(a|A)dmin|login).*?)/"=>array("controller"=>"controllers\\UserDashboardController","action"=>"routAction404","parameters"=>array(0),"name"=>"UserDashboardController-routAction404","cache"=>false,"duration"=>false),"/login/(index/)?"=>array("controller"=>"controllers\\BasicAuthController","action"=>"index","parameters"=>array(),"name"=>"BasicAuthController-index","cache"=>false,"duration"=>false),"/login/noAccess/(.+?)/"=>array("controller"=>"controllers\\BasicAuthController","action"=>"noAccess","parameters"=>array(0),"name"=>"BasicAuthController-noAccess","cache"=>false,"duration"=>false),"/login/message/(.+?)/(.+?)/(.+?)/(.*?)"=>array("controller"=>"controllers\\BasicAuthController","action"=>"message","parameters"=>array(0,1,2,"~3","~4"),"name"=>"BasicAuthController-message","cache"=>false,"duration"=>false),"/login/forgetConnection/"=>array("controller"=>"controllers\\BasicAuthController","action"=>"forgetConnection","parameters"=>array(),"name"=>"BasicAuthController-forgetConnection","cache"=>false,"duration"=>false),"/login/checkConnection/"=>array("controller"=>"controllers\\BasicAuthController","action"=>"checkConnection","parameters"=>array(),"name"=>"BasicAuthController-checkConnection","cache"=>false,"duration"=>false),"/login/info/(.*?)"=>array("controller"=>"controllers\\BasicAuthController","action"=>"info","parameters"=>array("~0"),"name"=>"BasicAuthController-info","cache"=>false,"duration"=>false),"/login/terminate/"=>array("controller"=>"controllers\\BasicAuthController","action"=>"terminate","parameters"=>array(),"name"=>"BasicAuthController-terminate","cache"=>false,"duration"=>false),"/login/badLogin/"=>array("controller"=>"controllers\\BasicAuthController","action"=>"badLogin","parameters"=>array(),"name"=>"BasicAuthController-badLogin","cache"=>false,"duration"=>false),"/login/connect/"=>array("controller"=>"controllers\\BasicAuthController","action"=>"connect","parameters"=>array(),"name"=>"BasicAuthController-connect","cache"=>false,"duration"=>false),"/login/insertJquerySemantic/"=>array("controller"=>"controllers\\BasicAuthController","action"=>"insertJquerySemantic","parameters"=>array(),"name"=>"BasicAuthController-insertJquerySemantic","cache"=>false,"duration"=>false));