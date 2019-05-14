<?php

namespace Drupal\layout_builder_at\Plugin\SectionStorage;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\FieldableEntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\layout_builder\Plugin\SectionStorage\OverridesSectionStorage;

class TranslatableOverridesSectionStorage extends OverridesSectionStorage {

  /**
   * {@inheritdoc}
   */
  protected function handleTranslationAccess(AccessResult $result, $operation, AccountInterface $account) {
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getSectionListFromId($id) {
    if (strpos($id, '.') !== FALSE) {
      list($entity_type_id, $entity_id) = explode('.', $id, 2);
      $entity = $this->entityTypeManager->getStorage($entity_type_id)->load($entity_id);
      // Get translation from context.
      $entity = \Drupal::service('entity.repository')->getTranslationFromContext($entity);
      if ($entity instanceof FieldableEntityInterface && $entity->hasField(static::FIELD_NAME)) {
        return $entity->get(static::FIELD_NAME);
      }
    }
    throw new \InvalidArgumentException(sprintf('The "%s" ID for the "%s" section storage type is invalid', $id, $this->getStorageType()));
  }

}
