from playwright.sync_api import sync_playwright
import json
import os
import time
from datetime import datetime

# Configuration
DEBUG_DIR = "startuplist_data"
os.makedirs(DEBUG_DIR, exist_ok=True)

def save_data(data, filename):
    """Sauvegarde les données extraites"""
    path = os.path.join(DEBUG_DIR, filename)
    with open(path, 'w', encoding='utf-8') as f:
        json.dump(data, f, indent=2, ensure_ascii=False)
    print(f"💾 Données sauvegardées: {path}")

def extract_stat_cards(page):
    """Extrait les données des cartes de statistiques"""
    print("📊 Extraction des cartes de statistiques...")
    
    cards_data = []
    cards = page.locator('div.grid.gap-4 > div.h-full').all()
    
    if not cards:
        print("⚠️ Aucune carte trouvée avec le sélecteur 'div.grid.gap-4 > div.h-full'")
        return cards_data, 0
    
    for i, card in enumerate(cards, 1):
        card_data = {
            "title": None,
            "subtitle": None,
            "value": None,
            "trend": {"direction": None, "value": None},
            "badge": None,
            "graph_values": [],
            "sectors": [],
            "insight": None,
            "timestamp": datetime.now().isoformat()
        }
        
        try:
            # Titre principal
            card_data["title"] = card.locator('div.tracking-tight').first.inner_text(timeout=5000)
            
            # Sous-titre
            card_data["subtitle"] = card.locator('p.text-muted-foreground').first.inner_text(timeout=3000)
            
            # Valeur principale
            card_data["value"] = card.locator('div.text-2xl').first.inner_text(timeout=3000)
            
            # Variation
            try:
                trend_icon = card.locator('svg').first.get_attribute("class", timeout=2000)
                card_data["trend"]["direction"] = "up" if "arrow-up" in (trend_icon or "") else "down"
                card_data["trend"]["value"] = card.locator('span.text-sm.font-medium').first.inner_text(timeout=2000)
            except:
                pass
            
            # Badge
            try:
                card_data["badge"] = card.locator('div.inline-flex.items-center.rounded-full').first.inner_text(timeout=2000)
            except:
                pass
            
            cards_data.append(card_data)
            print(f"✅ Carte {i} traitée: {card_data['title']}")
            
        except Exception as e:
            print(f"⚠️ Erreur sur la carte {i}: {str(e)}")
            cards_data.append(card_data)
    
    return cards_data, len(cards)

def extract_funding_data(page):
    """Extrait les données de la section Funding Activity"""
    print("💰 Extraction des données de funding...")
    
    funding_data = {
        "title": None,
        "subtitle": None,
        "time_period": None,
        "metrics": [],
        "footer_stats": {},
        "timestamp": datetime.now().isoformat()
    }
    
    try:
        # Vérifier si la section existe
        if page.locator('[data-walkthrough="funding-chart"]').count() == 0:
            print("⚠️ Section Funding Activity non trouvée")
            return funding_data
        
        # Titre et sous-titre
        funding_data["title"] = page.locator('[data-walkthrough="funding-chart"] h3').first.inner_text(timeout=5000)
        funding_data["subtitle"] = page.locator('[data-walkthrough="funding-chart"] p.text-sm').first.inner_text(timeout=5000)
        
        # Période sélectionnée
        funding_data["time_period"] = page.locator('[data-walkthrough="funding-chart"] button').first.inner_text(timeout=5000)
        
        # Métriques principales
        metric_cards = page.locator('[data-walkthrough="funding-chart"] div.grid.grid-cols-3 > div').all()
        for card in metric_cards:
            try:
                metric = {
                    "label": card.locator('span.text-xs').first.inner_text(timeout=2000),
                    "value": card.locator('div.font-semibold').first.inner_text(timeout=2000),
                    "description": card.locator('div.text-xs.text-muted-foreground').first.inner_text(timeout=2000)
                }
                funding_data["metrics"].append(metric)
            except Exception as e:
                print(f"⚠️ Erreur sur une métrique: {str(e)}")
        
        # Statistiques en bas
        try:
            footer = page.locator('[data-walkthrough="funding-chart"] div.flex.flex-wrap').first.inner_text(timeout=3000)
            parts = [p.strip() for p in footer.split('•') if p.strip()]
            if len(parts) >= 3:
                funding_data["footer_stats"] = {
                    "total_funding": parts[0].replace("Total Funding:", "").strip(),
                    "total_rounds": parts[1].replace("Rounds", "").strip(),
                    "total_startups": parts[2].replace("Startups", "").strip()
                }
        except:
            print("⚠️ Impossible d'extraire les statistiques du footer")
            
    except Exception as e:
        print(f"❌ Erreur majeure lors de l'extraction des données de funding: {str(e)}")
    
    return funding_data

def main():
    print("=== STARTUPLIST AFRICA DATA EXTRACTOR ===")
    start_time = time.time()
    
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=False)
        context = browser.new_context(
            viewport={"width": 1366, "height": 768},
            locale="fr-FR"
        )
        page = context.new_page()
        
        try:
            # Connexion
            print("🔑 Connexion en cours...")
            page.goto("https://app.startuplist.africa/login", timeout=60000)
            page.fill("input[type='email']", "samsiath13@gmail.com", timeout=5000)
            page.fill("input[type='password']", "samsiath@232004", timeout=5000)
            page.click("button[type='submit']", timeout=5000)
            
            # Vérification connexion
            try:
                page.wait_for_url("**/dashboard**", timeout=15000)
                print("✅ Connecté avec succès!")
            except:
                raise Exception("Échec de la connexion: URL du dashboard non atteinte")
            
            # Attente du chargement
            print("⏳ Attente du chargement du dashboard...")
            page.wait_for_selector('div.grid.gap-4', timeout=15000)
            time.sleep(2)  # Attente supplémentaire
            
            # Extraction des cartes
            cards_data, total_cards = extract_stat_cards(page)
            if cards_data:
                save_data(cards_data, "dashboard_stats.json")
                print(f"\n🎉 {len(cards_data)}/{total_cards} cartes extraites avec succès")
            
            # Extraction funding
            funding_data = extract_funding_data(page)
            if funding_data.get("metrics"):
                save_data(funding_data, "funding_activity.json")
                print("\n💵 Données de funding extraites:")
                for metric in funding_data["metrics"]:
                    print(f"• {metric['label']}: {metric['value']}")
            
            # Capture d'écran
            timestamp = int(time.time())
            page.screenshot(path=os.path.join(DEBUG_DIR, f"dashboard_{timestamp}.png"))
            print(f"\n📸 Capture sauvegardée: dashboard_{timestamp}.png")
            
        except Exception as e:
            print(f"\n❌ ERREUR: {str(e)}")
            page.screenshot(path=os.path.join(DEBUG_DIR, f"error_{int(time.time())}.png"))
        finally:
            browser.close()
    
    print(f"\n=== FIN (Durée: {time.time() - start_time:.2f}s) ===")

if __name__ == "__main__":
    main()