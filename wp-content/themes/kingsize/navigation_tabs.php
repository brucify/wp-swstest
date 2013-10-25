<?php

function get_navigation_tabs($active_tab) {
	/*$overview = '"btn btn-inverse"';
	$tickets = '"btn btn-inverse"';
	$create_ticket = '"btn btn-inverse"';
	$credits = '"btn btn-inverse"';
	
	switch($active_tab) {
		case 'overview':
			$overview = '"btn"';
			break;
		case 'tickets':
			$tickets = '"btn"';
			break;
		case 'create-ticket':
			$create_ticket = '"btn"';
			break;
		case 'credits':
			$credits = '"btn"';
			break;	
		default:
		break;
	}
	
	echo 
	'<nav>
    <td>
        <a class='.$overview.' href="../overview">Overview</a>
    </td>
    <td>
        <a class='.$tickets.' href="../tickets">Tickets</a>
    </td>
    <td>
        <a class='.$create_ticket.' href="../create-ticket">Create Ticket</a>
    </td>
    <td>
        <a class='.$credits.' href="../credits">Credits</a>
	</td>
	</nav>'; */
	$overview = '';
	$tickets = '';
	$create_ticket = '';
	$credits = '';
	
	switch($active_tab) {
		case 'overview':
			$overview = ' class="active"';
			break;
		case 'tickets':
			$tickets = ' class="active"';
			break;
		case 'create-ticket':
			$create_ticket = ' class="active"';
			break;
		case 'credits':
			$credits = ' class="active"';
			break;	
		default:
		break;
	}
	
	echo 
		'		
		<ul class="nav nav-pills" style="margin: 0px 0px;">
		<li'.$overview.'>
			<a href="../overview">Overview</a>
		</li>
		<li'.$tickets.'>
			<a href="../tickets">Tickets</a>
		</li>
		<li'.$create_ticket.'>
			<a href="../create-ticket">Create Ticket</a>
		</li>
		<li'.$credits.'>
			<a href="../credits">Credits</a>
		</li>
		</ul>
	';
} 
?>