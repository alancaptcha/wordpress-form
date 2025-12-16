document.addEventListener("DOMContentLoaded", function () {
  const dropdown = document.getElementsByName("alanforms_language")[0];

  const extraFields = document.querySelectorAll(
    ".alanforms_language_custom_override"
  );

  function toggleExtraFields() {
    const isVisible = dropdown.value === "custom";
    extraFields.forEach((field) => {
      field.style.display = isVisible ? "block" : "none";
    });
  }

  dropdown.addEventListener("change", toggleExtraFields);
  toggleExtraFields();
});
