import { Controller } from '@hotwired/stimulus';

/*
 * Menus déroulants de la navbar (balises <details>) :
 * - en ouvrir un referme les autres ;
 * - un clic en dehors de la navbar, ou la touche Échap, referme tout.
 */
export default class extends Controller {
    static targets = ['menu'];

    connect() {
        this.onOutsideClick = (event) => {
            if (!this.element.contains(event.target)) {
                this.closeAll();
            }
        };
        document.addEventListener('click', this.onOutsideClick);
    }

    disconnect() {
        document.removeEventListener('click', this.onOutsideClick);
    }

    // Appelée quand un <details> s'ouvre ou se ferme (événement "toggle")
    closeOthers(event) {
        const opened = event.currentTarget;
        if (!opened.open) {
            return;
        }
        this.menuTargets.forEach((menu) => {
            if (menu !== opened) {
                menu.open = false;
            }
        });
    }

    closeAll() {
        this.menuTargets.forEach((menu) => {
            menu.open = false;
        });
    }
}
