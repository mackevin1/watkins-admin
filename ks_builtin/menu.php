<?php

$library_file = realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR . "library.php";

include_once $library_file;

if(! isset ($ks_translate)) {
	$ks_translate = new ksTranslate ();
}

if (! isset ($mid )) {
	echo "<div class=\"alert alert-danger\"><i class=\"glyphicon glyphicon-exclamation-sign\"></i> Fatal Error! Menu ID (\$mid) is undefined. 
			Ensure that this variable is defined before including 'ks_builtin/menu.php'. 
			Usually this is defined in layout_header.php</div>";
	exit ();
}

$ks_session = CUSTOM_User::getSessionData ();
$usr_id = $ks_session ['USR_ID'];
$usr_role = $ks_session ['USR_ROLE'];

$menustyle = 1;

$objMenu = new KS_Menu ();
$objMenu->setId ( $mid );
if (! $objMenu->exists ()) {
	$alerterror = "<div class=\"alert alert-danger\">Error. Menu not found, ID \$mid '$mid' specified. Contact System Administrator to fix this.</div>";
	echo $alerterror;
}
$objMenu->select ();

// unserialize input layout
$optionlayout = unserialize ( $objMenu->getOption () );
$menulayout = $optionlayout ['menuo_layout'];

$objMenuitemExist = new KS_Menuitem ();
$objMenuitemExist->setSearchSqlWhere ( " mi_menuid='$mid' AND (mi_parentid IS NULL OR mi_parentid='') AND mi_notlogin = '1'" );
$objMenuitemExist->setSearchSortField ( "mi_order" );
$objMenuitemExist->setSearchSortOrder ( 'ASC' );
$objMenuitemExist->setSearchRecordsPerPage ( 1000 );
$arrMenuitemExist = $objMenuitemExist->search ();
$totMenuitemExist = count ( $arrMenuitemExist );

// check any menu for before login page
if (! $usr_id && ($totMenuitemExist == 0)) {
} else {
	
	// stackable Vertical
	if ($menulayout == 2) {
		$openmenu = "<div class=\"row\">";
		$openmenu .= "<div class=\"col-xs-12 col-sm-3 col-md-3\">";
		$openmenu .= "<nav class=\"navbar navbar-inverse\" role=\"navigation\">";
		$openmenu .= "<div class=\"navbar-header\">
						<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-ex1-collapse\">
						 <span class=\"sr-only\">Toggle navigation</span>
					      <span class=\"icon-bar\"></span>
					      <span class=\"icon-bar\"></span>
					      <span class=\"icon-bar\"></span>
					    </button></div>
    					<div class=\"collapse navbar-collapse navbar-ex1-collapse\">";
		$openmenu .= "<ul class=\"nav navbar-stacked\">";
		$closedmenu = "</ul></div>";
		$closedmenu .= "</nav>";
		$closedmenu .= "</div>";
		$closedmenu .= "</div>";
	} else {
		
		$openmenu = "<nav class=\"navbar navbar-inverse\" role=\"navigation\">";
		$openmenu .= "<div class=\"navbar-header\">
						<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-ex1-collapse\">
						 <span class=\"sr-only\">Toggle navigation</span>
					      <span class=\"icon-bar\"></span>
					      <span class=\"icon-bar\"></span>
					      <span class=\"icon-bar\"></span>
					    </button></div>
    					<div class=\"collapse navbar-collapse navbar-ex1-collapse\">";
		$openmenu .= "<ul class=\"nav navbar-nav\">";
		$closedmenu = "</ul></div></nav>";
	}
	
	if ($menustyle == 1) {
		
		$objMenuitem = new KS_Menuitem ();
		$objMenuitem->setSearchSqlWhere ( " mi_menuid='$mid' AND (mi_parentid IS NULL OR mi_parentid='')" );
		$objMenuitem->setSearchSortField ( "mi_order" );
		$objMenuitem->setSearchSortOrder ( 'ASC' );
		$objMenuitem->setSearchRecordsPerPage ( 1000 );
		$arrMenuitem = $objMenuitem->search ();
		$totMenuitem = count ( $arrMenuitem );
		
		if ($totMenuitem > 0) {
			echo $openmenu;
			$curMenuitem = new KS_Menuitem ();
			foreach ( $arrMenuitem as $curMenuitem ) {
				$menuitem_id = $curMenuitem->getId ();
				
				// submenu
				$sqlWhere = " mi_menuid='$mid' AND mi_parentid='$menuitem_id' ";
				$totSubMenuitem = 0;
				$objSubMenuitem = new KS_Menuitem ();
				$objSubMenuitem->setSearchSqlWhere ( $sqlWhere );
				$objSubMenuitem->setSearchRecordsPerPage ( 1000 );
				$arrSubMenuitem = $objSubMenuitem->search ();
				$totSubMenuitem = count ( $arrSubMenuitem );
				
				$roles = $curMenuitem->getRoles ();
				$tooltip = $curMenuitem->getTooltip ();
				
				// unserialize input icon
				$optionicon = unserialize ( $curMenuitem->getOption () );
				$menuicon = $optionicon ['mio_icon'];
				$displayicon = "";
				if ($menuicon) {
					$displayicon = "<i class=\"" . $menuicon . "\"></i> ";
				}
				//menuurl
				$urltype = $curMenuitem->getUrltype();
				$menuurl = "";
				if($urltype == 'internal'){
					$menuurl .= KSCONFIG_URL;
				}
				$menuurl .= $curMenuitem->getUrl ();
				
				$arrRolesSelected = array ();
				if ($roles) {
					$arrRolesSelected = explode ( ";", $roles );
					// menuitem
					if (count ( $arrRolesSelected ) > 0 && $usr_id) {
						if (in_array ( $usr_role, $arrRolesSelected )) {
							if ($curMenuitem->getUrltype () == "blank" || $curMenuitem->getUrl () == "") {
								if ($totSubMenuitem) {
									echo "\n\t<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" rel=\"tooltip\" href=\"#\" data-original-title=\"$tooltip\" /><div class=\"wrapword\">" . $displayicon . $ks_translate->_ ( $curMenuitem->getLabel () ) . "<b class=\"caret\"></b></div></a>";
								} else {
									echo "\n\t<li class=\"dropdown\"><a rel=\"tooltip\" href=\"#\" data-original-title=\"$tooltip\" /><div class=\"wrapword\">" . $displayicon . $ks_translate->_ ( $curMenuitem->getLabel () ) . "</div></a></li>";
								}
							} else {
								if ($totSubMenuitem) {
									echo "\n\t<li class=\"dropdown\"><a class=\"dropdown-toggle\" data-toggle=\"dropdown\" rel=\"tooltip\" href=\"#\" data-original-title=\"$tooltip\" /><div class=\"wrapword\">" . $displayicon . $ks_translate->_ ( $curMenuitem->getLabel () ) . "<b class=\"caret\"></b></div></a>";
								} else {
									echo "\n\t<li class=\"dropdown\"><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"" . $menuurl . "\"><div class=\"wrapword\">" . $displayicon . $ks_translate->_ ( $curMenuitem->getLabel () ) . "</div></a></li>";
								}
							}
						} else {
							continue;
						}
					}
				}
				
				$sqlWhere = " mi_menuid='$mid' AND mi_parentid='$menuitem_id' ";
				// menu not login
				if (! $usr_id && ($curMenuitem->getNotlogin () == 1)) {
					$sqlWhere = " mi_menuid='$mid' AND mi_parentid='$menuitem_id' AND mi_notlogin=1 ";
					
					// submenu
					$totSubMenuitem = 0;
					$objSubMenuitem = new KS_Menuitem ();
					$objSubMenuitem->setSearchSqlWhere ( $sqlWhere );
					$objSubMenuitem->setSearchRecordsPerPage ( 1000 );
					$arrSubMenuitem = $objSubMenuitem->search ();
					$totSubMenuitem = count ( $arrSubMenuitem );
					
					if ($totSubMenuitem) {
						echo "\n\t<li class=\"dropdown\"><a class=\"dropdown-toggle\" rel=\"tooltip\" data-original-title=\"$tooltip\" data-toggle=\"dropdown\" href=\"#\"><div class=\"wrapword\">" . $displayicon . $ks_translate->_ ( $curMenuitem->getLabel () ) . "<b class=\"caret\"></b></div></a>";
					} else {
						echo "\n\t<li class=\"dropdown\"><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"" . $menuurl . "\"><div class=\"wrapword\">" . $displayicon . $ks_translate->_ ( $curMenuitem->getLabel () ) . "</div></a></li>";
					}
				}
				
				$totSubMenuitem = 0;
				$objSubMenuitem = new KS_Menuitem ();
				$objSubMenuitem->setSearchSqlWhere ( $sqlWhere );
				$objSubMenuitem->setSearchSortField ( "mi_order" );
				$objSubMenuitem->setSearchSortOrder ( 'ASC' );
				$objSubMenuitem->setSearchRecordsPerPage ( 1000 );
				$arrSubMenuitem = $objSubMenuitem->search ();
				$totSubMenuitem = count ( $arrSubMenuitem );
				
				// sub-menuitem
				if ($totSubMenuitem > 0) {
					echo "\n\t\t<ul class=\"dropdown-menu\">";
					$curSubMenuitem = new KS_Menuitem ();
					foreach ( $arrSubMenuitem as $curSubMenuitem ) {
						
						$submenuitem_id = $curSubMenuitem->getId ();
						$tooltip = $curSubMenuitem->getTooltip ();
						
						// unserialize input icon
						$optionicon = unserialize ( $curSubMenuitem->getOption () );
						$menuicon = $optionicon ['mio_icon'];
						$displayicon = "";
						if ($menuicon) {
							$displayicon = "<i class=\"" . $menuicon . "\"></i> ";
						}
						
						//menuurl
						$urltype = $curSubMenuitem->getUrltype();
						$submenuurl = "";
						if($urltype == 'internal'){
							$submenuurl .= KSCONFIG_URL;
						}
						$submenuurl .= $curSubMenuitem->getUrl ();

						// submenu1
						$sqlWhere1 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id' ";
						$totSubMenuitem1 = 0;
						$objSubMenuitem1 = new KS_Menuitem ();
						$objSubMenuitem1->setSearchSqlWhere ( $sqlWhere1 );
						$objSubMenuitem1->setSearchRecordsPerPage ( 1000 );
						$arrSubMenuitem1 = $objSubMenuitem1->search ();
						$totSubMenuitem1 = count ( $arrSubMenuitem1 );
						
						$roles = $curSubMenuitem->getRoles ();
						$arrSubRolesSelected = array ();
						if ($roles) {
							$arrSubRolesSelected = explode ( ";", $roles );
							
							if (count ( $arrSubRolesSelected ) > 0 && $usr_id) {
								if (in_array ( $usr_role, $arrSubRolesSelected )) {
									if ($curSubMenuitem->getUrltype () == "blank" || $curSubMenuitem->getUrl () == "") {
										if ($totSubMenuitem1) {
											echo "\n\t\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem->getLabel () ) . "</a>";
										} else {
											echo "\n\t\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem->getLabel () ) . "</a></li>";
										}
									} elseif ($curSubMenuitem->getUrltype () == "separator") {
										echo "\n\t<li class=\"divider\"></li>";
									} else {
										if ($totSubMenuitem1) {
											echo "\n\t\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem->getLabel () ) . "</a>";
										} else {
											echo "\n\t\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"" . $submenuurl . "\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem->getLabel () ) . "</a></li>";
										}
									}
								}
							}
						}
						
						$sqlWhere1 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id' ";
						// sub menu not login
						if (! $usr_id && $curSubMenuitem->getNotlogin () == 1) {
							
							$sqlWhere1 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id' AND mi_notlogin=1 ";
							
							// submenu
							$totSubMenuitem1 = 0;
							$objSubMenuitem1 = new KS_Menuitem ();
							$objSubMenuitem1->setSearchSqlWhere ( $sqlWhere1 );
							$objSubMenuitem1->setSearchRecordsPerPage ( 1000 );
							$arrSubMenuitem1 = $objSubMenuitem1->search ();
							$totSubMenuitem1 = count ( $arrSubMenuitem1 );
							if ($totSubMenuitem1) {
								echo "\n\t\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem->getLabel () ) . "</a>";
							} else {
								echo "\n\t\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"" . $submenuurl . "\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem->getLabel () ) . "</a></li>";
							}
						}
						
						$totSubMenuitem1 = 0;
						$objSubMenuitem1 = new KS_Menuitem ();
						$objSubMenuitem1->setSearchSqlWhere ( $sqlWhere1 );
						$objSubMenuitem1->setSearchSortField ( "mi_order" );
						$objSubMenuitem1->setSearchSortOrder ( 'ASC' );
						$objSubMenuitem1->setSearchRecordsPerPage ( 1000 );
						$arrSubMenuitem1 = $objSubMenuitem1->search ();
						$totSubMenuitem1 = count ( $arrSubMenuitem1 );
						
						// sub-menuitem1
						if ($totSubMenuitem1 > 0) {
							echo "\n\t\t\t\t<ul class=\"dropdown-menu submenu-show submenu-hide\">";
							$curSubMenuitem1 = new KS_Menuitem ();
							foreach ( $arrSubMenuitem1 as $curSubMenuitem1 ) {
								
								$submenuitem_id1 = $curSubMenuitem1->getId ();
								$tooltip = $curSubMenuitem1->getTooltip ();
								
								// unserialize input icon
								$optionicon = unserialize ( $curSubMenuitem1->getOption () );
								$menuicon = $optionicon ['mio_icon'];
								$displayicon = "";
								if ($menuicon) {
									$displayicon = "<i class=\"" . $menuicon . "\"></i> ";
								}
								
								//menuurl
								$urltype = $curSubMenuitem1->getUrltype();
								$submen1uurl = "";
								if($urltype == 'internal'){
									$submen1uurl .= KSCONFIG_URL;
								}
								$submen1uurl .= $curSubMenuitem1->getUrl ();

								// submenu1
								$sqlWhere2 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id1' ";
								$totSubMenuitem2 = 0;
								$objSubMenuitem2 = new KS_Menuitem ();
								$objSubMenuitem2->setSearchSqlWhere ( $sqlWhere2 );
								$objSubMenuitem2->setSearchRecordsPerPage ( 1000 );
								$arrSubMenuitem2 = $objSubMenuitem2->search ();
								$totSubMenuitem2 = count ( $arrSubMenuitem2 );
								
								$roles = $curSubMenuitem1->getRoles ();
								$arrSubRolesSelected = array ();
								if ($roles) {
									$arrSubRolesSelected = explode ( ";", $roles );
									
									if (count ( $arrSubRolesSelected ) > 0 && $usr_id) {
										if (in_array ( $usr_role, $arrSubRolesSelected )) {
											if ($curSubMenuitem1->getUrltype () == "blank" || $curSubMenuitem1->getUrl () == "") {
												if ($totSubMenuitem2) {
													echo "\n\t\t\t\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem1->getLabel () ) . "</a>";
												} else {
													echo "\n\t\t\t\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem1->getLabel () ) . "</a></li>";
												}
											} elseif ($curSubMenuitem1->getUrltype () == "separator") {
												echo "\n\t<li class=\"divider\"></li>";
											} else {
												if ($totSubMenuitem2) {
													echo "\n\t\t\t\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem1->getLabel () ) . "</a>";
												} else {
													echo "\n\t\t\t\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"" . $submen1uurl . "\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem1->getLabel () ) . "</a></li>";
												}
											}
										}
									}
								}
								
								$sqlWhere2 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id1' ";
								// sub menu not login
								if (! $usr_id && $curSubMenuitem1->getNotlogin () == 1) {
									
									$sqlWhere2 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id1' AND mi_notlogin=1 ";
									
									// submenu
									$totSubMenuitem2 = 0;
									$objSubMenuitem2 = new KS_Menuitem ();
									$objSubMenuitem2->setSearchSqlWhere ( $sqlWhere2 );
									$objSubMenuitem2->setSearchRecordsPerPage ( 1000 );
									$arrSubMenuitem2 = $objSubMenuitem2->search ();
									$totSubMenuitem2 = count ( $arrSubMenuitem2 );
									if ($totSubMenuitem2) {
										echo "\n\t\t\t\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem1->getLabel () ) . "</a>";
									} else {
										echo "\n\t\t\t\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"" . $submen1uurl . "\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem1->getLabel () ) . "</a></li>";
									}
								}
								
								$totSubMenuitem2 = 0;
								$objSubMenuitem2 = new KS_Menuitem ();
								$objSubMenuitem2->setSearchSqlWhere ( $sqlWhere2 );
								$objSubMenuitem2->setSearchSortField ( "mi_order" );
								$objSubMenuitem2->setSearchSortOrder ( 'ASC' );
								$objSubMenuitem2->setSearchRecordsPerPage ( 1000 );
								$arrSubMenuitem2 = $objSubMenuitem2->search ();
								$totSubMenuitem2 = count ( $arrSubMenuitem2 );
								
								// sub-menuitem2
								if ($totSubMenuitem2 > 0) {
									echo "\n\t\t\t\t\t\t\t<ul class=\"dropdown-menu submenu-show submenu-hide\">";
									$curSubMenuitem2 = new KS_Menuitem ();
									foreach ( $arrSubMenuitem2 as $curSubMenuitem2 ) {
										
										$submenuitem_id2 = $curSubMenuitem2->getId ();
										$tooltip = $curSubMenuitem2->getTooltip ();
										
										// unserialize input icon
										$optionicon = unserialize ( $curSubMenuitem2->getOption () );
										$menuicon = $optionicon ['mio_icon'];
										$displayicon = "";
										if ($menuicon) {
											$displayicon = "<i class=\"" . $menuicon . "\"></i> ";
										}
										
										//menuurl
										$urltype = $curSubMenuitem2->getUrltype();
										$submen2uurl = "";
										if($urltype == 'internal'){
											$submen2uurl .= KSCONFIG_URL;
										}
										$submen2uurl .= $curSubMenuitem2->getUrl ();

										// submenu3
										$sqlWhere3 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id2' ";
										$totSubMenuitem3 = 0;
										$objSubMenuitem3 = new KS_Menuitem ();
										$objSubMenuitem3->setSearchSqlWhere ( $sqlWhere3 );
										$objSubMenuitem3->setSearchRecordsPerPage ( 1000 );
										$arrSubMenuitem3 = $objSubMenuitem3->search ();
										$totSubMenuitem3 = count ( $arrSubMenuitem3 );
										
										$roles = $curSubMenuitem2->getRoles ();
										$arrSubRolesSelected = array ();
										if ($roles) {
											$arrSubRolesSelected = explode ( ";", $roles );
											
											if (count ( $arrSubRolesSelected ) > 0 && $usr_id) {
												if (in_array ( $usr_role, $arrSubRolesSelected )) {
													if ($curSubMenuitem2->getUrltype () == "blank" || $curSubMenuitem2->getUrl () == "") {
														if ($totSubMenuitem3) {
															echo "\n\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem2->getLabel () ) . "</a>";
														} else {
															echo "\n\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem2->getLabel () ) . "</a></li>";
														}
													} elseif ($curSubMenuitem2->getUrltype () == "separator") {
														echo "\n\t<li class=\"divider\"></li>";
													} else {
														if ($totSubMenuitem3) {
															echo "\n\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem2->getLabel () ) . "</a>";
														} else {
															echo "\n\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"" . $submen2uurl . "\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem2->getLabel () ) . "</a></li>";
														}
													}
												}
											}
										}
										
										$sqlWhere3 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id2' ";
										// sub menu not login
										if (! $usr_id && $curSubMenuitem2->getNotlogin () == 1) {
											
											$sqlWhere3 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id2' AND mi_notlogin=1 ";
											
											// submenu
											$totSubMenuitem3 = 0;
											$objSubMenuitem3 = new KS_Menuitem ();
											$objSubMenuitem3->setSearchSqlWhere ( $sqlWhere3 );
											$objSubMenuitem3->setSearchRecordsPerPage ( 1000 );
											$arrSubMenuitem3 = $objSubMenuitem3->search ();
											$totSubMenuitem3 = count ( $arrSubMenuitem3 );
											if ($totSubMenuitem3) {
												echo "\n\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem2->getLabel () ) . "</a>";
											} else {
												echo "\n\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"" . $submen2uurl . "\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem2->getLabel () ) . "</a></li>";
											}
										}
										
										$totSubMenuitem3 = 0;
										$objSubMenuitem3 = new KS_Menuitem ();
										$objSubMenuitem3->setSearchSqlWhere ( $sqlWhere3 );
										$objSubMenuitem3->setSearchSortField ( "mi_order" );
										$objSubMenuitem3->setSearchSortOrder ( 'ASC' );
										$objSubMenuitem3->setSearchRecordsPerPage ( 1000 );
										$arrSubMenuitem3 = $objSubMenuitem3->search ();
										$totSubMenuitem3 = count ( $arrSubMenuitem3 );
										
										// sub-menuitem3
										if ($totSubMenuitem3 > 0) {
											echo "\n<ul class=\"dropdown-menu submenu-show submenu-hide\">";
											$curSubMenuitem3 = new KS_Menuitem ();
											foreach ( $arrSubMenuitem3 as $curSubMenuitem3 ) {
												
												$submenuitem_id3 = $curSubMenuitem3->getId ();
												$tooltip = $curSubMenuitem3->getTooltip ();
												
												// unserialize input icon
												$optionicon = unserialize ( $curSubMenuitem3->getOption () );
												$menuicon = $optionicon ['mio_icon'];
												$displayicon = "";
												if ($menuicon) {
													$displayicon = "<i class=\"" . $menuicon . "\"></i> ";
												}
												
												//menuurl
												$urltype = $curSubMenuitem3->getUrltype();
												$submen3uurl = "";
												if($urltype == 'internal'){
													$submen3uurl .= KSCONFIG_URL;
												}
												$submen3uurl .= $curSubMenuitem3->getUrl ();

												// submenu4
												$sqlWhere4 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id3' ";
												$totSubMenuitem4 = 0;
												$objSubMenuitem4 = new KS_Menuitem ();
												$objSubMenuitem4->setSearchSqlWhere ( $sqlWhere4 );
												$objSubMenuitem4->setSearchRecordsPerPage ( 1000 );
												$arrSubMenuitem4 = $objSubMenuitem4->search ();
												$totSubMenuitem4 = count ( $arrSubMenuitem4 );
												
												$roles = $curSubMenuitem3->getRoles ();
												$arrSubRolesSelected = array ();
												if ($roles) {
													$arrSubRolesSelected = explode ( ";", $roles );
													
													if (count ( $arrSubRolesSelected ) > 0 && $usr_id) {
														if (in_array ( $usr_role, $arrSubRolesSelected )) {
															if ($curSubMenuitem3->getUrltype () == "blank" || $curSubMenuitem3->getUrl () == "") {
																if ($totSubMenuitem4) {
																	echo "\n\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem3->getLabel () ) . "</a>";
																} else {
																	echo "\n\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem3->getLabel () ) . "</a></li>";
																}
															} elseif ($curSubMenuitem3->getUrltype () == "separator") {
																echo "\n\t<li class=\"divider\"></li>";
															} else {
																if ($totSubMenuitem4) {
																	echo "\n\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem3->getLabel () ) . "</a>";
																} else {
																	echo "\n\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"" . $submen3uurl . "\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem3->getLabel () ) . "</a></li>";
																}
															}
														}
													}
												}
												
												$sqlWhere4 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id3' ";
												// sub menu not login
												if (! $usr_id && $curSubMenuitem3->getNotlogin () == 1) {
													
													$sqlWhere4 = " mi_menuid='$mid' AND mi_parentid='$submenuitem_id3' AND mi_notlogin=1 ";
													
													// submenu
													$totSubMenuitem4 = 0;
													$objSubMenuitem4 = new KS_Menuitem ();
													$objSubMenuitem4->setSearchSqlWhere ( $sqlWhere4 );
													$objSubMenuitem4->setSearchRecordsPerPage ( 1000 );
													$arrSubMenuitem4 = $objSubMenuitem4->search ();
													$totSubMenuitem4 = count ( $arrSubMenuitem4 );
													if ($totSubMenuitem4) {
														echo "\n\t\t<li class=\"dropdown submenu\"><a data-toggle=\"dropdown\" rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"#\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem3->getLabel () ) . "</a>";
													} else {
														echo "\n\t\t<li><a rel=\"tooltip\" data-original-title=\"$tooltip\" href=\"" . $submen3uurl . "\">" . $displayicon . $ks_translate->_ ( $curSubMenuitem3->getLabel () ) . "</a></li>";
													}
												}
											}
											echo "\n</ul></li>";
										} else {
											// echo "</li>";
										}
									}
									echo "\n</ul></li>";
								} else {
									// echo "</li>";
								}
							}
							echo "\n</ul></li>";
						} else {
							// echo "</li>";
						}
					}
					echo "\n</ul></li>";
				} else {
					// echo "</li>";
				}
			}
			echo $closedmenu;
		}
		?>

		<?php
	} else {
		?>

	<?php
		
		echo "<div class=\"alert alert-danger\">
<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>
Failed to load menu.Please select to use bootstrap at ks_option.</div>";
		
		?>

<?php } }?>
<script>
$(document).ready(function () {

	  jQuery('.submenu').hover(function () {

	        jQuery(this).children('ul').removeClass('submenu-hide').addClass('submenu-show');
	    }, function () {
	        jQuery(this).children('ul').removeClass('.submenu-show').addClass('submenu-hide');
	    }).find("a:first").append(" &raquo; ");

    $("[rel=tooltip]").tooltip();
});
</script>