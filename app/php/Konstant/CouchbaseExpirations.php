<?php

  namespace App\Konstant;

  class CouchbaseExpirations
  {
    /**
     * Expiration in seconds
     */

    const DISTRICTS__GET_DISTRICTS_BY_CITY = 10;

    const VENUES__GET_VENUES_BY_CITY = 10;

    const EVENTS__GET_EVENTS_BY_FILTER = 10;
    const EVENTS__GET_BY_VENUE_URLNAME = 86400;
  }
