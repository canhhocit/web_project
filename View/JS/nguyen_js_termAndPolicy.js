function modalterm() {
    const existingModal_term = document.getElementById("modalOverlay_term");

    if (!existingModal_term) {
        fetch("/web_project/components/nguyen_modal_term.html")
            .then((res) => {
                if (!res.ok) throw new Error("Không tải được file modal term");
                return res.text();
            })
            .then((html) => {
                document.body.insertAdjacentHTML("beforeend", html);

                initModal_term();
            })
            .catch((err) => console.error("Lỗi tải modal term:", err));
    } else {
        openModal_term();
    }
}

function initModal_term() {
    console.log("into func initmodal_term");
    const modal = document.getElementById("modalOverlay_term");
    const closeTermX = document.getElementById("closeTermX");
    const btnTerm = document.getElementById("btn_term");

    openModal_term();

    closeTermX.onclick = () => closeModal_term();
    modal.addEventListener("click", (e) => {
        if (e.target === modal) closeModal_term();
    });

    btnTerm.onclick = () => {
        closeModal_term();
    };
}

function openModal_term() {
    console.log("into func openmodal_term");
    const modal = document.getElementById("modalOverlay_term");
    modal.classList.add("active");
    document.body.style.overflow = "hidden";
}

function closeModal_term() {
    const modal = document.getElementById("modalOverlay_term");
    modal.classList.remove("active");
    document.body.style.overflow = "auto";
}
// =========================== title_here ==================================================

// =========================================================================================

function modalpolicy() {
    const existingModal_policy = document.getElementById("modalOverlay_policy");
    console.log("into func modalpolicy" + existingModal_policy);

    if (!existingModal_policy) {
        fetch("/web_project/components/nguyen_modal_policy.html")
            .then((res) => {
                if (!res.ok)
                    throw new Error("Không tải được file modal policy");
                return res.text();
            })
            .then((html) => {
                document.body.insertAdjacentHTML("beforeend", html);

                initModal_policy();
            })
            .catch((err) => console.error("Lỗi tải modal policy:", err));
    } else {
        openModal_policy();
    }
}

function initModal_policy() {
    console.log("into func initmodal_policy");
    const modal = document.getElementById("modalOverlay_policy");
    const closePolicyX = document.getElementById("closePolicyX");
    const btnPolicy = document.getElementById("btn_policy");

    openModal_policy();
    closePolicyX.onclick = () => closeModal_policy();
    modal.addEventListener("click", (e) => {
        if (e.target === modal) closeModal_policy();
    });

    btnPolicy.onclick = () => {
        closeModal_policy();
    };
}

function openModal_policy() {
    console.log("into func openmodal_policyAAA");
    const modal = document.getElementById("modalOverlay_policy");
    modal.classList.add("active");
    document.body.style.overflow = "hidden";
}

function closeModal_policy() {
    const modal = document.getElementById("modalOverlay_policy");
    modal.classList.remove("active");
    document.body.style.overflow = "auto";
}
