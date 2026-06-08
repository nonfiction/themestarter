// disable welcome guide
wp.data.select("core/edit-post").isFeatureActive("welcomeGuide") &&
  wp.data.dispatch("core/edit-post").toggleFeature("welcomeGuide");
