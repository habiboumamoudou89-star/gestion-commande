<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu — {{ $menu->etablissement->nom }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root { --primary: #e67e22; --primary-dark: #ca6f1e; }
        * { box-sizing: border-box; }
        body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; padding-bottom: 100px; }

        .etab-header {
            background: var(--primary); color: white;
            padding: 1.2rem 1rem; position: sticky; top: 0; z-index: 100;
        }
        .etab-header .logo {
            width: 52px; height: 52px; border-radius: 50%;
            object-fit: cover; border: 2px solid rgba(255,255,255,.4);
        }
        .etab-header h1 { font-size: 1.15rem; margin: 0; font-weight: 700; }
        .etab-header .table-badge { font-size: .8rem; opacity: .85; }

        .cat-nav {
            background: white; border-bottom: 1px solid #e2e8f0;
            overflow-x: auto; white-space: nowrap;
            padding: .5rem .75rem; position: sticky; top: 82px; z-index: 90;
        }
        .cat-nav::-webkit-scrollbar { height: 3px; }
        .cat-nav::-webkit-scrollbar-thumb { background: #cbd5e1; }
        .cat-nav .btn {
            border-radius: 20px; font-size: .85rem; padding: .35rem .9rem;
            margin-right: .4rem; border: 1px solid #e2e8f0; transition: all .2s;
        }
        .cat-nav .btn.active { background: var(--primary); border-color: var(--primary); color: white; }

        .cat-section { padding: 1rem .75rem .5rem; }
        .cat-section h2 { font-size: 1rem; font-weight: 700; color: #374151; margin-bottom: .75rem; }

        .article-card {
            background: white; border-radius: 12px; border: 2px solid transparent;
            transition: border-color .2s, transform .15s; overflow: hidden; cursor: pointer;
        }
        .article-card:hover { transform: translateY(-1px); }
        .article-card.selected { border-color: var(--primary); }

        .article-card .img-wrap { height: 130px; overflow: hidden; background: #f1f5f9; }
        .article-card .img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .article-card .img-placeholder {
            width: 100%; height: 100%; display: flex;
            align-items: center; justify-content: center;
            color: #94a3b8; font-size: 2.5rem;
        }
        .article-card .prix { color: var(--primary); font-weight: 700; }

        .qty-control { display: flex; align-items: center; gap: .4rem; }
        .qty-control button {
            width: 28px; height: 28px; border-radius: 50%;
            border: 1px solid var(--primary); background: white;
            color: var(--primary); font-size: 1rem; line-height: 1;
            display: flex; align-items: center; justify-content: center;
            transition: all .15s; padding: 0;
        }
        .qty-control button:hover { background: var(--primary); color: white; }
        .qty-control .qty-num { font-weight: 700; min-width: 20px; text-align: center; }

        .option-item {
            padding: .6rem .75rem; border: 1.5px solid #e2e8f0; border-radius: 8px;
            cursor: pointer; transition: all .15s; margin-bottom: .4rem;
        }
        .option-item:hover { border-color: var(--primary); }
        .option-item.selected { border-color: var(--primary); background: #fff7ed; }
        .option-item input { display: none; }

        .panier-btn {
            position: fixed; bottom: 1.5rem; left: 50%; transform: translateX(-50%);
            background: var(--primary); color: white; border: none;
            border-radius: 50px; padding: .9rem 2rem; font-size: 1rem; font-weight: 600;
            box-shadow: 0 4px 20px rgba(230,126,34,.5);
            transition: all .2s; z-index: 200; min-width: 260px; display: none;
        }
        .panier-btn:hover { background: var(--primary-dark); transform: translateX(-50%) translateY(-2px); }
        .panier-badge { background: white; color: var(--primary); border-radius: 50px; padding: 2px 10px; font-size: .85rem; }

        .offcanvas-bottom { height: auto; max-height: 85vh; border-radius: 20px 20px 0 0; }
    </style>
</head>
<body>

<header class="etab-header d-flex align-items-center gap-3">
    @if($menu->etablissement->logo)
        <img src="{{ $menu->etablissement->logo_url }}" class="logo" alt="Logo">
    @endif
    <div>
        <h1>{{ $menu->etablissement->nom }}</h1>
        <div class="table-badge"><i class="bi bi-geo-alt me-1"></i>Table {{ $table->numero }}</div>
    </div>
</header>

<nav class="cat-nav" id="catNav">
    @foreach($menu->categoriesRacines as $cat)
    <button class="btn btn-sm cat-btn" data-target="#cat-{{ $cat->id }}">{{ $cat->nom }}</button>
    @endforeach
</nav>

<div id="menuContent">
    @foreach($menu->categoriesRacines as $cat)
    <section class="cat-section" id="cat-{{ $cat->id }}">
        <h2>{{ $cat->nom }}</h2>
        <div class="row g-2">
            @foreach($cat->articles as $article)
            @if($article->disponible)
            <div class="col-6 col-md-4">
                <div class="article-card h-100"
                     data-id="{{ $article->id }}"
                     data-nom="{{ $article->nom }}"
                     data-prix="{{ $article->prix }}"
                     data-options='@json($article->options)'
                     onclick="ouvrirArticle(this)">
                    <div class="img-wrap">
                        @if($article->image)
                            <img src="{{ $article->image_url }}" alt="{{ $article->nom }}" loading="lazy">
                        @else
                            <div class="img-placeholder"><i class="bi bi-egg-fried"></i></div>
                        @endif
                    </div>
                    <div class="p-2">
                        <div class="fw-semibold small">{{ $article->nom }}</div>
                        @if($article->description)
                            <div class="text-muted" style="font-size:.75rem">{{ Str::limit($article->description, 45) }}</div>
                        @endif
                        <div class="prix mt-1">{{ number_format($article->prix, 2) }} MAD</div>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        @foreach($cat->sousCategories as $sousCat)
        @if($sousCat->articles->where('disponible', true)->count() > 0)
        <div class="mt-3">
            <div class="text-muted small fw-semibold mb-2 text-uppercase" style="letter-spacing:.05em">
                {{ $sousCat->nom }}
            </div>
            <div class="row g-2">
                @foreach($sousCat->articles->where('disponible', true) as $article)
                <div class="col-6 col-md-4">
                    <div class="article-card h-100"
                         data-id="{{ $article->id }}"
                         data-nom="{{ $article->nom }}"
                         data-prix="{{ $article->prix }}"
                         data-options='@json($article->options)'
                         onclick="ouvrirArticle(this)">
                        <div class="img-wrap">
                            @if($article->image)
                                <img src="{{ $article->image_url }}" alt="{{ $article->nom }}" loading="lazy">
                            @else
                                <div class="img-placeholder"><i class="bi bi-egg-fried"></i></div>
                            @endif
                        </div>
                        <div class="p-2">
                            <div class="fw-semibold small">{{ $article->nom }}</div>
                            <div class="prix mt-1">{{ number_format($article->prix, 2) }} MAD</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </section>
    @endforeach
</div>

<button class="panier-btn" id="panierBtn" onclick="ouvrirPanier()">
    <i class="bi bi-basket me-2"></i>
    Voir le panier
    <span class="panier-badge ms-2" id="panierBadge">0</span>
    <span class="ms-2" id="panierTotal">0.00 MAD</span>
</button>

<div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasPanier" data-bs-scroll="true">
    <div class="offcanvas-header border-bottom px-4">
        <h5 class="offcanvas-title fw-bold"><i class="bi bi-basket me-2"></i>Mon panier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body px-4" id="panierContenu"></div>
    <div class="px-4 pb-4 pt-2 border-top">
        <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
            <span>Total</span>
            <span id="panierTotalOffcanvas" class="text-warning">0.00 MAD</span>
        </div>
        <button class="btn btn-warning w-100 py-3 fw-bold fs-5" onclick="soumettrePanier()">
            <i class="bi bi-send me-2"></i>Commander
        </button>
    </div>
</div>

<div class="modal fade" id="modalArticle" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalArticleNom"></h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalArticleOptions"></div>
            <div class="modal-footer border-0 pt-0">
                <div class="d-flex align-items-center gap-3 w-100">
                    <div class="qty-control">
                        <button onclick="changeModalQty(-1)"><i class="bi bi-dash"></i></button>
                        <span class="qty-num" id="modalQty">1</span>
                        <button onclick="changeModalQty(1)"><i class="bi bi-plus"></i></button>
                    </div>
                    <button class="btn btn-warning flex-grow-1 fw-bold" onclick="ajouterAuPanier()">
                        Ajouter — <span id="modalPrix"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="formCommande" action="{{ route('client.commande.store') }}" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="table_id" value="{{ $table->id }}">
    <div id="formItems"></div>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
let panier = [];
let articleCourant = null;
let modalQty = 1;

document.querySelectorAll('.cat-btn').forEach((btn, i) => {
    if (i === 0) btn.classList.add('active');
    btn.addEventListener('click', () => {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelector(btn.dataset.target)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const id = '#' + entry.target.id;
            document.querySelectorAll('.cat-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.target === id);
            });
        }
    });
}, { threshold: .3, rootMargin: '-100px 0px -60% 0px' });
document.querySelectorAll('.cat-section').forEach(s => observer.observe(s));

function ouvrirArticle(el) {
    articleCourant = {
        id: el.dataset.id,
        nom: el.dataset.nom,
        prix: parseFloat(el.dataset.prix),
        options: JSON.parse(el.dataset.options || '[]')
    };
    modalQty = 1;

    document.getElementById('modalArticleNom').textContent = articleCourant.nom;
    document.getElementById('modalQty').textContent = 1;
    majModalPrix();

    const optionsEl = document.getElementById('modalArticleOptions');
    if (articleCourant.options.length === 0) {
        optionsEl.innerHTML = '<p class="text-muted small mb-0">Pas d\'options disponibles pour cet article.</p>';
    } else {
        const groupes = {};
        articleCourant.options.forEach(opt => {
            const g = opt.groupe || 'Options';
            if (!groupes[g]) groupes[g] = [];
            groupes[g].push(opt);
        });

        let html = '';
        Object.entries(groupes).forEach(([groupe, opts]) => {
            html += `<p class="fw-semibold small mb-2 text-uppercase text-muted" style="letter-spacing:.05em">${groupe}</p>`;
            opts.forEach(opt => {
                html += `
                <label class="option-item d-flex justify-content-between align-items-center" data-prix="${opt.prix_supplementaire}">
                    <div>
                        <span class="fw-semibold small">${opt.nom}</span>
                        ${opt.obligatoire ? '<span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">Requis</span>' : ''}
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        ${opt.prix_supplementaire > 0 ? `<span class="text-success small">+${parseFloat(opt.prix_supplementaire).toFixed(2)} MAD</span>` : ''}
                        <input type="checkbox" value="${opt.id}" class="opt-check">
                        <div class="rounded-2 border" style="width:20px;height:20px;border-color:var(--primary) !important;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-check text-warning check-icon" style="display:none;font-size:.8rem"></i>
                        </div>
                    </div>
                </label>`;
            });
        });
        optionsEl.innerHTML = html;

        optionsEl.querySelectorAll('.option-item').forEach(item => {
            item.addEventListener('click', () => {
                item.classList.toggle('selected');
                const icon = item.querySelector('.check-icon');
                icon.style.display = item.classList.contains('selected') ? 'block' : 'none';
                majModalPrix();
            });
        });
    }

    new bootstrap.Modal(document.getElementById('modalArticle')).show();
}

function changeModalQty(delta) {
    modalQty = Math.max(1, modalQty + delta);
    document.getElementById('modalQty').textContent = modalQty;
    majModalPrix();
}

function majModalPrix() {
    if (!articleCourant) return;
    let total = articleCourant.prix;
    document.querySelectorAll('.option-item.selected').forEach(item => {
        total += parseFloat(item.dataset.prix || 0);
    });
    document.getElementById('modalPrix').textContent = (total * modalQty).toFixed(2) + ' MAD';
}

function ajouterAuPanier() {
    const optionsSelectionnees = [];
    let prixOptions = 0;
    document.querySelectorAll('.option-item.selected').forEach(item => {
        const input = item.querySelector('.opt-check');
        optionsSelectionnees.push(parseInt(input.value));
        prixOptions += parseFloat(item.dataset.prix || 0);
    });

    panier.push({
        article_id: parseInt(articleCourant.id),
        nom: articleCourant.nom,
        prix: articleCourant.prix,
        quantite: modalQty,
        options: optionsSelectionnees,
        prixOptions,
        sousTotal: (articleCourant.prix + prixOptions) * modalQty
    });

    bootstrap.Modal.getInstance(document.getElementById('modalArticle')).hide();
    majPanier();
}

function majPanier() {
    const total = panier.reduce((s, i) => s + i.sousTotal, 0);
    const count = panier.reduce((s, i) => s + i.quantite, 0);

    document.getElementById('panierBadge').textContent = count;
    document.getElementById('panierTotal').textContent = total.toFixed(2) + ' MAD';
    document.getElementById('panierTotalOffcanvas').textContent = total.toFixed(2) + ' MAD';
    document.getElementById('panierBtn').style.display = panier.length > 0 ? 'block' : 'none';

    let html = '';
    panier.forEach((item, idx) => {
        html += `
        <div class="d-flex justify-content-between align-items-start mb-3 pb-2 border-bottom">
            <div class="flex-grow-1">
                <div class="fw-semibold">${item.nom}</div>
                <div class="text-muted small">Qté: ${item.quantite} × ${item.prix.toFixed(2)} MAD</div>
                ${item.prixOptions > 0 ? `<div class="text-muted small">Options: +${item.prixOptions.toFixed(2)} MAD</div>` : ''}
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold text-warning">${item.sousTotal.toFixed(2)} MAD</span>
                <button class="btn btn-sm btn-outline-danger rounded-circle" onclick="retirerDuPanier(${idx})" style="width:28px;height:28px;padding:0">
                    <i class="bi bi-x" style="font-size:.85rem"></i>
                </button>
            </div>
        </div>`;
    });
    if (panier.length === 0) {
        html = '<p class="text-center text-muted py-4"><i class="bi bi-basket2 me-2"></i>Panier vide</p>';
    }
    document.getElementById('panierContenu').innerHTML = html;
}

function retirerDuPanier(idx) {
    panier.splice(idx, 1);
    majPanier();
}

function ouvrirPanier() {
    new bootstrap.Offcanvas(document.getElementById('offcanvasPanier')).show();
}

function soumettrePanier() {
    if (panier.length === 0) return;

    const form = document.getElementById('formCommande');
    const itemsEl = document.getElementById('formItems');
    itemsEl.innerHTML = '';

    panier.forEach((item, i) => {
        itemsEl.innerHTML += `
            <input type="hidden" name="items[${i}][article_id]" value="${item.article_id}">
            <input type="hidden" name="items[${i}][quantite]" value="${item.quantite}">
            <input type="hidden" name="items[${i}][prix]" value="${item.prix}">
        `;
        item.options.forEach(optId => {
            itemsEl.innerHTML += `<input type="hidden" name="items[${i}][options][]" value="${optId}">`;
        });
    });

    form.submit();
}

majPanier();
</script>
</body>
</html>
