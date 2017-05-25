<?php

namespace Degustation\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DegustationUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
