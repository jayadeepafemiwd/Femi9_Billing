{{-- resources/views/products/_history_data.blade.php --}}
{{-- $data = array to render, $compare = the other side for diff, $side = 'old'|'new' --}}
@php
  if (!function_exists('renderHistoryValue')) {
    function renderHistoryValue($val, int $depth = 0): string {
      if (is_null($val))    return '<span style="color:#c0c4cd;font-style:italic;">null</span>';
      if (is_bool($val))    return '<span style="color:#7c3aed;">' . ($val ? 'true' : 'false') . '</span>';
      if (is_numeric($val)) return '<span style="color:#d97706;">' . e($val) . '</span>';
      if (is_string($val))  return '<span style="color:#2d3748;">' . e($val) . '</span>';
      if (is_array($val)) {
        if (empty($val)) return '<span style="color:#9aa5b8;">[]</span>';
        $lines       = [];
        $indent      = str_repeat('  ', $depth + 1);
        $closeIndent = str_repeat('  ', $depth);
        foreach ($val as $k => $v) {
          $keyStr  = is_int($k) ? '' : '<span style="color:#7c8db0;font-weight:600;">' . e($k) . ':</span> ';
          $lines[] = $indent . $keyStr . renderHistoryValue($v, $depth + 1);
        }
        return "[\n" . implode("\n", $lines) . "\n{$closeIndent}]";
      }
      return e((string) $val);
    }
  }
@endphp

@if(is_array($data))
  <div style="font-family:'Consolas','Courier New',monospace;font-size:12px;line-height:1.7;">
    @foreach($data as $key => $value)
      @php
        $isDiff = isset($compare) && array_key_exists($key, $compare) && $compare[$key] != $value;
        $isNew  = isset($compare) && !array_key_exists($key, $compare);

        $rowBg   = ($isDiff || $isNew)
          ? 'background:' . ($side === 'old' ? 'rgba(239,68,68,0.06)' : 'rgba(52,211,153,0.08)') . ';'
          : '';

        $valColor = '#2d3748';
        if ($isDiff && $side === 'old') $valColor = '#ef4444';
        if ($isDiff && $side === 'new') $valColor = '#10b981';

        $valExtra = '';
        if ($isDiff && $side === 'old') $valExtra = 'text-decoration:line-through;opacity:0.8;';
        if ($isDiff && $side === 'new') $valExtra = 'font-weight:600;';
      @endphp

      <div style="display:flex;gap:6px;align-items:flex-start;padding:2px 4px;margin:0 -4px;border-radius:3px;{{ $rowBg }}">

        <span style="color:#7c8db0;font-weight:600;flex-shrink:0;min-width:110px;">{{ $key }}:</span>

        <span style="color:{{ $valColor }};{{ $valExtra }}">
          @if(is_array($value))
            {!! renderHistoryValue($value) !!}
          @elseif(is_null($value))
            <span style="color:#c0c4cd;font-style:italic;">null</span>
          @elseif(is_bool($value))
            <span style="color:#7c3aed;">{{ $value ? 'true' : 'false' }}</span>
          @else
            {{ $value }}
          @endif
        </span>

        @if($isNew && $side === 'new')
          <span style="font-size:10px;background:#dcfce7;color:#16a34a;border-radius:3px;padding:1px 5px;flex-shrink:0;">new</span>
        @endif

      </div>
    @endforeach
  </div>
@else
  {{ $data }}
@endif