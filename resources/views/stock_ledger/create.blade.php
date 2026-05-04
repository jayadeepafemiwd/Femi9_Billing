<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Stock Ledger</title>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<style>
  :root {
    --bg: #0d0f12;
    --surface: #141720;
    --surface2: #1c2030;
    --border: #2a2f40;
    --border2: #353c52;
    --text: #e8ecf4;
    --muted: #6b7491;
    --accent: #4f8cff;
    --accent2: #7c5cff;
    --green: #2ecc8a;
    --red: #ff5f5f;
    --amber: #f5a623;
    --teal: #00c9b1;
    --pink: #ff6eb4;
    --mono: 'IBM Plex Mono', monospace;
    --sans: 'DM Sans', sans-serif;
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { background: var(--bg); color: var(--text); font-family: var(--sans); min-height: 100vh; }

  .header {
    border-bottom: 1px solid var(--border);
    padding: 20px 32px;
    display: flex; align-items: center; gap: 16px;
    background: var(--surface);
  }
  .header-icon {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, var(--accent), var(--accent2));
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
  }
  .header h1 { font-size: 18px; font-weight: 600; letter-spacing: -0.3px; }
  .header p  { font-size: 12px; color: var(--muted); margin-top: 2px; }

  .main { padding: 28px 32px; max-width: 1400px; }

  .filter-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 24px;
  }
  .filter-row { display: flex; gap: 14px; align-items: flex-end; flex-wrap: wrap; }
  .field { display: flex; flex-direction: column; gap: 6px; }
  .field label { font-size: 11px; font-weight: 500; color: var(--muted); letter-spacing: 0.5px; text-transform: uppercase; }
  .field input, .field select {
    height: 40px; padding: 0 14px;
    background: var(--surface2);
    border: 1px solid var(--border2);
    border-radius: 9px;
    color: var(--text);
    font-family: var(--sans); font-size: 14px;
    min-width: 160px; outline: none;
    transition: border-color 0.15s;
  }
  .field input:focus, .field select:focus { border-color: var(--accent); }
  .field select option { background: var(--surface2); }

  .btn {
    height: 40px; padding: 0 20px;
    border-radius: 9px; border: none;
    font-family: var(--sans); font-size: 14px; font-weight: 500;
    cursor: pointer; transition: all 0.15s;
  }
  .btn-primary { background: var(--accent); color: #fff; }
  .btn-primary:hover { background: #3a7aee; transform: translateY(-1px); }
  .btn-primary:active { transform: translateY(0); }
  .btn-secondary { background: var(--surface2); color: var(--text); border: 1px solid var(--border2); }
  .btn-secondary:hover { border-color: var(--border); background: var(--border); }
  .btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

  .summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px; margin-bottom: 24px;
  }
  .metric {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 16px 18px;
    position: relative; overflow: hidden;
  }
  .metric::before {
    content: ''; position: absolute;
    top: 0; left: 0; right: 0; height: 2px;
  }
  .metric.total::before { background: var(--accent); }
  .metric.in::before    { background: var(--green); }
  .metric.out::before   { background: var(--red); }
  .metric.net::before   { background: var(--amber); }
  .metric-label { font-size: 11px; color: var(--muted); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
  .metric-val   { font-family: var(--mono); font-size: 22px; font-weight: 500; }
  .metric-val.pos   { color: var(--green); }
  .metric-val.neg   { color: var(--red); }
  .metric-val.amber { color: var(--amber); }
  .metric-val.blue  { color: var(--accent); }

  .table-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px; overflow: hidden;
  }
  .table-header {
    padding: 14px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
  }
  .table-header span { font-size: 13px; color: var(--muted); font-family: var(--mono); }

  table { width: 100%; border-collapse: collapse; }
  thead tr { background: var(--surface2); }
  th {
    padding: 11px 16px; text-align: left;
    font-size: 11px; font-weight: 500; color: var(--muted);
    text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;
  }
  td { padding: 13px 16px; font-size: 13px; border-bottom: 1px solid var(--border); }
  tbody tr:last-child td { border-bottom: none; }
  tbody tr:hover { background: var(--surface2); }

  .badge {
    display: inline-block; padding: 3px 10px;
    border-radius: 20px; font-size: 11px; font-weight: 500;
    font-family: var(--mono); letter-spacing: 0.3px;
  }
  .badge-opening         { background: #1a2a4a; color: var(--accent);  border: 1px solid #2a3f70; }
  .badge-sale            { background: #2a1a1a; color: var(--red);     border: 1px solid #4a2a2a; }
  .badge-purchase        { background: #1a2a1a; color: var(--green);   border: 1px solid #2a4a2a; }
  .badge-sale_return     { background: #2a2210; color: var(--amber);   border: 1px solid #4a3a20; }
  .badge-purchase_return { background: #1a2a1a; color: #7eff9e;        border: 1px solid #2a4a2a; }
  .badge-transfer_in     { background: #1a2a2a; color: var(--teal);    border: 1px solid #2a4a4a; }
  .badge-transfer_out    { background: #2a1a2a; color: var(--pink);    border: 1px solid #4a2a4a; }
  .badge-adjustment      { background: #22182a; color: #c084fc;        border: 1px solid #3a2a50; }
  .badge-commit          { background: #1e1e2a; color: #818cf8;        border: 1px solid #2e2e50; }
  .badge-uncommit        { background: #2a1e2a; color: #e879f9;        border: 1px solid #4a2e4a; }

  .qty-pos  { color: var(--green); font-family: var(--mono); font-weight: 500; }
  .qty-neg  { color: var(--red);   font-family: var(--mono); font-weight: 500; }
  .qty-num  { font-family: var(--mono); color: var(--muted); }
  .id-cell  { font-family: var(--mono); font-size: 12px; color: var(--muted); }
  .date-cell{ font-family: var(--mono); font-size: 12px; white-space: nowrap; }
  .ref-cell { font-family: var(--mono); font-size: 11px; color: var(--teal); }
  .notes-cell { color: var(--muted); font-size: 12px; max-width: 160px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

  .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); font-size: 14px; }
  .empty-state .icon { font-size: 36px; margin-bottom: 12px; opacity: 0.4; }

  .loader { display: none; text-align: center; padding: 50px; color: var(--muted); font-size: 13px; }
  .spinner {
    width: 28px; height: 28px;
    border: 2px solid var(--border2);
    border-top-color: var(--accent);
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    margin: 0 auto 12px;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  .error-state { display: none; text-align: center; padding: 40px 20px; color: var(--red); font-size: 13px; }

  @media (max-width: 900px) {
    .main { padding: 16px; }
    .summary-grid { grid-template-columns: repeat(2, 1fr); }
    .filter-row { flex-direction: column; }
    .field input, .field select { min-width: 100%; }
  }
</style>
</head>
<body>

<div class="header">
  <div class="header-icon">📒</div>
  <div>
    <h1>Stock Ledger</h1>
    <p>Transaction history by date range</p>
  </div>
</div>

<div class="main">

  {{-- Filter --}}
  <div class="filter-card">
    <div class="filter-row">
      <div class="field">
        <label>From date</label>
        <input type="date" id="from_date" value="{{ date('Y-m-01') }}"/>
      </div>
      <div class="field">
        <label>To date</label>
        <input type="date" id="to_date" value="{{ date('Y-m-d') }}"/>
      </div>
      <div class="field">
        <label>Transaction type</label>
        <select id="txn_type">
          <option value="">All types</option>
          <option value="opening">Opening</option>
          <option value="sale">Sale</option>
          <option value="purchase">Purchase</option>
          <option value="sale_return">Sale return</option>
          <option value="purchase_return">Purchase return</option>
          <option value="transfer_in">Transfer in</option>
          <option value="transfer_out">Transfer out</option>
          <option value="adjustment">Adjustment</option>
          <option value="commit">Commit</option>
          <option value="uncommit">Uncommit</option>
        </select>
      </div>
      <div class="field">
        <label>Item ID (optional)</label>
        <input type="number" id="item_id" placeholder="e.g. 5" style="min-width:120px"/>
      </div>
      <button class="btn btn-primary" id="searchBtn" onclick="doSearch()">Search</button>
      <button class="btn btn-secondary" onclick="doClear()">Clear</button>
    </div>
  </div>

  {{-- Summary --}}
  <div class="summary-grid" id="summary" style="display:none">
    <div class="metric total">
      <div class="metric-label">Total transactions</div>
      <div class="metric-val blue" id="s-total">0</div>
    </div>
    <div class="metric in">
      <div class="metric-label">Qty in (+)</div>
      <div class="metric-val pos" id="s-in">0</div>
    </div>
    <div class="metric out">
      <div class="metric-label">Qty out (–)</div>
      <div class="metric-val neg" id="s-out">0</div>
    </div>
    <div class="metric net">
      <div class="metric-label">Net change</div>
      <div class="metric-val amber" id="s-net">0</div>
    </div>
  </div>

  {{-- Table --}}
  <div class="table-card">
    <div class="table-header">
      <span id="result-label">— no search yet —</span>
    </div>
    <div class="loader" id="loader">
      <div class="spinner"></div>
      Loading transactions...
    </div>
    <div class="error-state" id="error-state">⚠ Failed to load. Check API connection.</div>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Item</th>
          <th>Location</th>
          <th>Variant</th>
          <th>Type</th>
          <th>Reference</th>
          <th>Qty change</th>
          <th>Stock after</th>
          <th>Notes</th>
        </tr>
      </thead>
      <tbody id="tbody">
        <tr>
          <td colspan="10">
            <div class="empty-state">
              <div class="icon">🔍</div>
              Select a date range and click Search
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

</div>

<script>
const API_BASE = window.location.origin + '/api/stock-ledger';
  async function doSearch() {
    const from = document.getElementById('from_date').value;
    const to   = document.getElementById('to_date').value;
    const type = document.getElementById('txn_type').value;
    const item = document.getElementById('item_id').value;

    if (!from || !to) { alert('Please select both From and To dates.'); return; }
    if (from > to)    { alert('From date cannot be after To date.'); return; }

    setLoading(true);

    const params = new URLSearchParams({ from_date: from, to_date: to });
    if (type) params.append('transaction_type', type);
    if (item) params.append('item_id', item);

    try {
      const res = await fetch(`${API_BASE}?${params}`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const json = await res.json();
      renderSummary(json.summary);
      renderTable(json.data, from, to);
      document.getElementById('error-state').style.display = 'none';
    } catch (e) {
      console.error(e);
      document.getElementById('error-state').style.display = 'block';
      document.getElementById('tbody').innerHTML = '';
      document.getElementById('summary').style.display = 'none';
      document.getElementById('result-label').textContent = 'Error loading data';
    } finally {
      setLoading(false);
    }
  }

  function renderSummary(s) {
    document.getElementById('summary').style.display = 'grid';
    document.getElementById('s-total').textContent = s.total_transactions;
    document.getElementById('s-in').textContent    = '+' + parseFloat(s.total_in).toFixed(2);
    document.getElementById('s-out').textContent   = parseFloat(s.total_out).toFixed(2);
    const net   = parseFloat(s.net_change);
    const netEl = document.getElementById('s-net');
    netEl.textContent = (net >= 0 ? '+' : '') + net.toFixed(2);
  }

  function renderTable(rows, from, to) {
    document.getElementById('result-label').textContent =
      rows.length
        ? `${rows.length} transaction${rows.length > 1 ? 's' : ''} · ${from} → ${to}`
        : `No transactions · ${from} → ${to}`;

    const tbody = document.getElementById('tbody');

    if (!rows.length) {
      tbody.innerHTML = `<tr><td colspan="10"><div class="empty-state"><div class="icon">📭</div>No transactions found for this range</div></td></tr>`;
      return;
    }

    tbody.innerHTML = rows.map(r => {
      const qty      = parseFloat(r.qty_change);
      const after    = parseFloat(r.stock_on_hand_after);
      const date     = r.transaction_date ? r.transaction_date.split('T')[0] : '—';
      const itemName = r.item?.name      ?? `Item #${r.item_id}`;
      const locName  = r.location?.location_name ?? `Loc #${r.location_id}`;
      const varName  = r.variant_id      ? `#${r.variant_id}` : '—';
      const ref      = r.reference_type  ? `${r.reference_type} #${r.reference_id}` : '—';
      const notes    = r.notes           ?? '—';

      return `<tr>
        <td class="id-cell">${r.id}</td>
        <td class="date-cell">${date}</td>
        <td><strong style="font-size:13px">${itemName}</strong></td>
        <td style="font-size:12px;color:var(--muted)">${locName}</td>
        <td style="font-size:12px;color:var(--muted)">${varName}</td>
        <td><span class="badge badge-${r.transaction_type}">${r.transaction_type.replace(/_/g,' ')}</span></td>
        <td class="ref-cell">${ref}</td>
        <td class="${qty >= 0 ? 'qty-pos' : 'qty-neg'}">${qty >= 0 ? '+' : ''}${qty.toFixed(4)}</td>
        <td class="qty-num">${after.toFixed(4)}</td>
        <td class="notes-cell" title="${notes}">${notes}</td>
      </tr>`;
    }).join('');
  }

  function setLoading(on) {
    document.getElementById('loader').style.display  = on ? 'block' : 'none';
    document.getElementById('tbody').style.display   = on ? 'none'  : '';
    document.getElementById('searchBtn').disabled    = on;
  }

  function doClear() {
    document.getElementById('from_date').value = '{{ date('Y-m-01') }}';
    document.getElementById('to_date').value   = '{{ date('Y-m-d') }}';
    document.getElementById('txn_type').value  = '';
    document.getElementById('item_id').value   = '';
    document.getElementById('summary').style.display     = 'none';
    document.getElementById('error-state').style.display = 'none';
    document.getElementById('result-label').textContent  = '— no search yet —';
    document.getElementById('tbody').innerHTML = `<tr><td colspan="10"><div class="empty-state"><div class="icon">🔍</div>Select a date range and click Search</div></td></tr>`;
  }
</script>
</body>
</html>