<?php

namespace Drupal\activity_creator\Controller;

use Drupal\activity_creator\ActivityNotifications;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Notifications controller.
 */
class NotificationsController extends ControllerBase {

  protected $activityNotifications;

  /**
   * NotificationsController constructor.
   */
  public function __construct(AccountProxyInterface $current_user, ActivityNotifications $activity_notifications) {
    $this->currentUser = $current_user;
    $this->activityNotifications = $activity_notifications;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('activity_creator.activity_notifications')
    );
  }

  /**
   * Ajax callback to mark notifications as read.
   */
  public function readNotificationCallback() {
    $account = $this->currentUser;

    // Dependency injection.
    $activity_notifications = $this->activityNotifications;
    $remaining_notifications = $activity_notifications->markAllNotificationsAsSeen($account);

    // Create AJAX Response object.
    // @todo: Implement a Ajax command instead and call via addCommand().
    $response = new AjaxResponse();
    $data = [
      'remaining_notifications' => $remaining_notifications,
    ];
    $response->setData($data);

    // Return ajax response.
    return $response;
  }

}
