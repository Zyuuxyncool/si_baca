<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Backwards-compatible alias model: keep RuangTekaTemplate as a thin alias
 * to the new RuangTeka template model so existing references don't break.
 */
class RuangTekaTemplate extends RuangTeka {}
