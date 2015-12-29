<?php

namespace App\ProjectActor\TemplateConfiguration;

interface TemplateConfigurationInterface {

	public function all();

	public function first($templateName);

	public function get($templateName);

	// public function duplicate($existingTemplate, $templateName);

	// public function add($name, $template, $baseTemplate, $globalTemplate, $iconFolder);

}

