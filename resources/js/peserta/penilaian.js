window.downloadPDF = function(username) {
    const originalElement = document.getElementById('certificate-print');
    
    const container = document.createElement('div');
    container.style.position = 'absolute';
    container.style.left = '0';
    container.style.top = '0';
    container.style.width = '1123px';
    container.style.height = '794px';
    container.style.zIndex = '-9999';
    container.style.opacity = '0';
    container.style.pointerEvents = 'none';
    
    const wrapper = document.createElement('div');
    wrapper.className = 'pdf-wrapper';
    
    const clone = originalElement.cloneNode(true);
    clone.classList.remove('hidden-print-element');
    clone.style.display = 'flex';
    
    wrapper.appendChild(clone);
    container.appendChild(wrapper);
    document.body.appendChild(container);

    try {
        const opt = {
            margin: 0,
            filename: `Hasil_Penilaian_${username}.pdf`,
            image: { type: 'jpeg', quality: 1.0 },
            html2canvas: { 
                scale: 2, 
                useCORS: true,
                windowWidth: 1123,
                windowHeight: 794,
                width: 1123,
                height: 794,
                x: 0,
                y: 0,
                scrollX: 0,
                scrollY: 0,
                onclone: function(doc) {
                    const template = document.getElementById('pdf-style-template');
                    if (template) {
                        const styleClone = template.content.cloneNode(true);
                        doc.head.appendChild(styleClone);
                    }
                }
            },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().set(opt).from(wrapper).save().then(() => {
            document.body.removeChild(container);
        });
        
    } catch (error) {
        console.error("Failed to generate PDF:", error);
        alert("Gagal mengunduh PDF. Silakan coba lagi.");
        document.body.removeChild(container);
    }
}

