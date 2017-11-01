<?php

namespace Drupal\aurorasync;

interface Comparable
{
    public function hash();

    public function igual( Comparable $other ) : bool;

}
