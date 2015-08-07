<?php
Breadcrumbs::register('home', function($breadcrumbs) {
	$breadcrumbs->push('Home', url('/'));
});

Breadcrumbs::register('patients', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Patients', url('/user/patient'));
});

Breadcrumbs::register('patients-edit', function($breadcrumbs, $token) {
	$breadcrumbs->parent('patients');
	$breadcrumbs->push('Edit patient', route('patients-edit', $token));
});

Breadcrumbs::register('cases-edit', function($breadcrumbs, $token) {
    $breadcrumbs->parent('patients-edit', $token);
    $breadcrumbs->push('Edit Cases');
});

Breadcrumbs::register('dashboard', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Dashboard', url('/user/dashboard'));
});

Breadcrumbs::register('profile', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Profile', url('/user'));
});

Breadcrumbs::register('profile-edit', function($breadcrumbs) {
	$breadcrumbs->parent('profile');
	$breadcrumbs->push('Edit profile');
});

Breadcrumbs::register('report', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Reports', url('/user/report'));
});

Breadcrumbs::register('activity', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Activities', url('/user/activity'));
});

Breadcrumbs::register('referralgrid', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Referral Grid', url('/user/referralgrid'));
});

Breadcrumbs::register('clinic', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Referral Source', url('/user/clinic'));
});

Breadcrumbs::register('clinic-edit', function($breadcrumbs) {
	$breadcrumbs->parent('clinic');
	$breadcrumbs->push('Edit Office');
});

Breadcrumbs::register('doctor-edit', function($breadcrumbs) {
    $breadcrumbs->parent('clinic');
    $breadcrumbs->push('Edit Doctor');
});

Breadcrumbs::register('account-creation', function($breadcrumbs) {
	$breadcrumbs->parent('home');
	$breadcrumbs->push('Account creation');
});

