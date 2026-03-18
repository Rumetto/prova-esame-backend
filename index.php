<?php

require_once __DIR__ . '/config/cors.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/utils/helpers.php';
require_once __DIR__ . '/utils/response.php';
require_once __DIR__ . '/utils/validator.php';
require_once __DIR__ . '/utils/jwt.php';

require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/middleware/RoleMiddleware.php';

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Event.php';
require_once __DIR__ . '/models/Registration.php';

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/EventController.php';
require_once __DIR__ . '/controllers/RegistrationController.php';
require_once __DIR__ . '/controllers/CheckinController.php';
require_once __DIR__ . '/controllers/StatsController.php';

require_once __DIR__ . '/routes/api.php';