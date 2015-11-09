<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $page_title; ?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url('bootstrap/css/bootstrap.min.css'); ?>">
		<style>
			.navbar-inverse {
				background-color: #600000;
				border-color: #480808;
			}
			
			.navbar-inverse .navbar-nav > .active > a,
			.navbar-inverse .navbar-nav > .active:hover > a{
				background-color: #900000;
			}
			
			.input-group { 
				width: 100%;
			}
			.input-group-addon{
				width: 100px;
			}
			body{
				background-image: url(<?php echo base_url('images/WoodFine0010.jpg'); ?>);;
			}
			.panel {
				background-color: rgba(0, 0, 0, 0.5);
				border-color: #000;
			}
			.panel-default>.panel-heading{
				background-color: rgba(169, 68, 66, 0.75);
				border-color: #000;
			}
		</style>
    	<script src="<?php echo base_url('jquery/jquery-2.1.4.min.js');?>"></script>
    	<script src="<?php echo base_url('bootstrap/js/bootstrap.min.js');?>"></script>
		<script>
		$(function () {
			$('.list-group.checked-list-box .list-group-item').each(function () {
				
				// Settings
				var $widget = $(this),
					$checkbox = $('<input type="checkbox" class="hidden" />'),
					color = ($widget.data('color') ? $widget.data('color') : "primary"),
					style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
					name = ($widget.data('name') ? $widget.data('name') : null),
					checked = ($widget.data('checked') ? $widget.data('checked') : null),
					settings = {
						on: {
							icon: 'glyphicon glyphicon-check'
						},
						off: {
							icon: 'glyphicon glyphicon-unchecked'
						}
					};
					
				$widget.css('cursor', 'pointer')
				if (name != null)
					$checkbox.attr('name', name);
				
				$widget.append($checkbox);
		
				// Event Handlers
				$widget.on('click', function () {
					$checkbox.prop('checked', !$checkbox.is(':checked'));
					$checkbox.triggerHandler('change');
					updateDisplay();
				});
				$checkbox.on('change', function () {
					updateDisplay();
				});
		
				// Actions
				function updateDisplay() {
					var isChecked = $checkbox.is(':checked');
		
					// Set the button's state
					$widget.data('state', (isChecked) ? "on" : "off");
		
					// Set the button's icon
					$widget.find('.state-icon')
						.removeClass()
						.addClass('state-icon ' + settings[$widget.data('state')].icon);
		
					// Update the button's color
					if (isChecked) {
						$widget.addClass(style + color + ' active');
					} else {
						$widget.removeClass(style + color + ' active');
					}
				}
		
				// Initialization
				function init() {
					
					if ($widget.data('checked') == true) {
						$checkbox.prop('checked', !$checkbox.is(':checked'));
					}
					
					updateDisplay();
		
					// Inject the icon if applicable
					if ($widget.find('.state-icon').length == 0) {
						$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
					}
				}
				init();
			});
			
			$('#get-checked-data').on('click', function(event) {
				event.preventDefault(); 
				var checkedItems = {}, counter = 0;
				$("#check-list-box li.active").each(function(idx, li) {
					checkedItems[counter] = $(li).text();
					counter++;
				});
				$('#display-json').html(JSON.stringify(checkedItems, null, '\t'));
			});
		});
		</script>
		<style>
			.state-icon {
				left: -5px;
			}
			.list-group-item-primary {
				color: rgb(255, 255, 255);
				background-color: rgb(66, 139, 202);
			}
		</style>
	</head>
	<body>
		<div class="container no-padding">