<?php

$app = new Slim\App([
  'debug' => true
]);

$container = $app->getContainer();

$container['db'] = function ($c) {
  return DB::connect();
};

// Register Twig View helper
$container['view'] = function ($c) {
  $view = new \Slim\Views\Twig(__DIR__ . '/templates', [
      'cache' => __DIR__ . '/cache/_views'
  ]);

  // Instantiate and add Slim specific extension
  $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

  return $view;
};

/**
 * Home route
 */
$app->get('/', function ($request, $response) {
  $e = $this->db->getEmbedCode();
  return $this->view->render($response, 'home.html', [
    'embed_code' => $e
  ]);
});

/**
 * Setup route. Renders form for embed code setup
 */
$app->get('/setup', function ($request, $response) {
  $e = $this->db->getEmbedCode();
  return $this->view->render($response, 'setup.html', [
    'embed_code' => $e
  ]);
});

/**
 * Setup form handler
 */
$app->post('/setup', function ($request, $response) {
  $embed_code = $request->getParsedBody()['embed_code_value'];
  if (is_null($embed_code)) {
    return $response->withRedirect('/setup');
  }
  $this->db->setEmbedCode($embed_code);
  return $response->withRedirect('/');
});

/**
 * Doctor dashboard
 */
$app->get('/dashboard/doctor', function ($request, $response) {
  return $this->view->render($response, 'dashboard_doctor.html', [
    'user' => [
      'role' => 'Doctor'
    ],
    'title' => 'Doctor Dashboard'
  ]);
});

/**
 * Patient dashboard
 */
$app->get('/dashboard/patient', function ($request, $response) {
  return $this->view->render($response, 'dashboard_patient.html', [
    'user' => [
      'role' => 'Patient'
    ],
    'title' => 'Patient Dashboard'
  ]);
});

/**
 * Meetings create form handler
 */
$app->post('/meetings', function ($request, $response) {
  $body = $request->getParsedBody();
  $id = $this->db->addMeeting($body['start_time'], $body['end_time']);
  return $response->withRedirect('/dashboard/doctor');
});

$app->run();
