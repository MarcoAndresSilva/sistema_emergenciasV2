// generar_pdf.js

// Clase GeneradorPDF
export class GeneradorPDF {
  // WARNING: las imagenes tiene que estar en formato base64 para que se muestren
    constructor(config = {}) {
        this.defaultConfig = {
            margin: 1,
            filename: "documento.pdf",
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { dpi: 192, letterRendering: true },
            jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
        };
        this.config = { ...this.defaultConfig, ...config };
    }

    /**
     * Genera un PDF a partir de contenido HTML.
     * @param {string|HTMLElement} content - Contenido en formato HTML o elemento DOM.
     */
    generar(content) {
        // TODO: Comprobar que el contenido sea un string HTML o un elemento DOM
        if (typeof content === "string") {
            html2pdf()
                .from(content)
                .set(this.config)
                .save();
        } else if (content instanceof HTMLElement) {
            html2pdf()
                .from(content)
                .set(this.config)
                .save();
        } else {
            throw new Error("El contenido debe ser un string HTML o un elemento DOM.");
        }
    }
}
