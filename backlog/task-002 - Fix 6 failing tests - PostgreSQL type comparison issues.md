---
id: 2
title: Fix 6 failing tests - PostgreSQL type comparison issues
status: To Do
priority: medium
assignees:
  - '@claude'
labels:
  - bug
  - tests
  - postgresql
subtasks: []
dependencies: []
blocked_by: []
created_date: '2025-12-13T05:40:45.376Z'
updated_date: '2025-12-13T05:40:55.736Z'
changelog:
  - timestamp: '2025-12-13T05:40:45.376Z'
    action: created
    details: Task created
    user: system
  - timestamp: '2025-12-13T05:40:55.736Z'
    action: modified
    details: Task updated
    user: AI
acceptance_criteria: []
ai_notes: >
  **2025-12-13T05:40:55.735Z** - ## Tests qui échouent


  Les 6 tests échouent car ils comparent des valeurs avec `toBe()` (comparaison
  stricte) mais PostgreSQL retourne des strings pour les valeurs numériques
  agrégées (ex: '600.00' au lieu de 600).


  ### Fichiers concernés:

  - tests/Feature/Livewire/Meals/CreateTest.php (lignes 69, 116)


  ### Solution suggérée:

  Utiliser `toEqual()` au lieu de `toBe()` pour les comparaisons numériques, ou
  caster les valeurs.
---

