<?php
namespace TYPO3\Typo3Dk\ViewHelpers\Uri;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Typo3Dk".         *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Resource\Resource;

/**
 * A extended resource view helper with cache busting
 */
class ResourceViewHelper extends \TYPO3\Fluid\ViewHelpers\Uri\ResourceViewHelper {

	/**
	 * Renders a URI to the resource and adds a version parameter to the URI if $addVersion is set
	 *
	 * @param string $path The location of the resource, can be either a path relative to the Public resource directory of the package or a resource://... URI
	 * @param string $package Target package key. If not set, the current package key will be used
	 * @param Resource|\TYPO3\Flow\Resource\Resource $resource If specified, this resource object is used instead of the path and package information
	 * @param boolean $localize Whether resource localization should be attempted or not
	 * @param boolean $addVersion Add a version number as a cache buster to the generated URI to enable flushing of resources with infinite expiration dates
	 * @throws \TYPO3\Fluid\Core\ViewHelper\Exception\InvalidVariableException
	 * @return string The absolute URI to the resource
	 */
	public function render($path = NULL, $package = NULL, Resource $resource = NULL, $localize = TRUE, $addVersion = TRUE) {
		$uri = parent::render($path, $package, $resource, $localize);
		if ($addVersion === TRUE && $resource === NULL) {
			if ($package === NULL) {
				$package = $this->controllerContext->getRequest()->getControllerPackageKey();
			}
			$resourceUri = 'resource://' . $package . '/Public/' . $path;
			try {
				$resourceStats = stat($resourceUri);
			} catch (\Exception $e) {
				$resourceStats = FALSE;
			}
			if ($resourceStats !== FALSE) {
				$mtime = $resourceStats['mtime'];
				$uri = $uri . '?' . $mtime;
			}
		}
		return $uri;
	}
}