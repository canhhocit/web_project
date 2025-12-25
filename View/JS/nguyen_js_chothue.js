async function fetchCarsByTab(tabIndex) {
    const res = await fetch(
        `/Controller/thuexe_controller.php?tab=${tabIndex}`
    );
    const data = await res.json();
    return data;
}
