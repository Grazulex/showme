---
id: 1
title: Fix DatabaseSeeder incompatible with configurations table
status: Done
priority: high
assignees:
  - '@claude'
labels:
  - bug
  - database
subtasks: []
dependencies: []
blocked_by: []
created_date: '2025-12-13T05:37:56.342Z'
updated_date: '2025-12-13T05:40:38.933Z'
closed_date: '2025-12-13T05:40:38.933Z'
changelog:
  - timestamp: '2025-12-13T05:37:56.342Z'
    action: created
    details: Task created
    user: system
  - timestamp: '2025-12-13T05:38:12.415Z'
    action: modified
    details: Task updated
    user: AI
  - timestamp: '2025-12-13T05:38:19.675Z'
    action: modified
    details: Task updated
    user: AI
  - timestamp: '2025-12-13T05:40:38.933Z'
    action: updated
    details: 'status: To Do → Done'
    user: user
acceptance_criteria: []
ai_plan: >-
  ## Plan d'implémentation


  ### Objectif

  Corriger le DatabaseSeeder qui utilisait des colonnes inexistantes (key,
  value) pour la table configurations.


  ### Problème identifié

  Le seeder essayait d'insérer des colonnes `key` et `value` alors que la table
  `configurations` a les colonnes:

  - topic_weight

  - topic_calorie_in

  - topic_calorie_out

  - user_id


  ### Solution appliquée

  Remplacer les 3 appels Configuration::factory()->create() avec key/value par
  un seul appel avec les bonnes colonnes.


  ### Fichier modifié

  - database/seeders/DatabaseSeeder.php (lignes 93-99)
ai_review: >-
  ## Self-Review


  ### Complété

  - [x] Identifié le problème: colonnes key/value inexistantes dans table
  configurations

  - [x] Corrigé le seeder pour utiliser topic_weight, topic_calorie_in,
  topic_calorie_out

  - [x] Testé avec migrate:fresh --seed: OK


  ### Tests effectués

  - migrate:fresh --seed sur PostgreSQL: OK

  - Application démarre correctement: OK


  ### Notes

  Ce bug était probablement dû à une ancienne structure de table qui a été
  modifiée par migration sans mettre à jour le seeder.
---

