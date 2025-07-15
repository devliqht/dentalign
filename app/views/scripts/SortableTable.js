function toggleTableCollapse(sectionId) {
  const tableContainer = document.getElementById(
    `table-container-${sectionId}`,
  );
  const collapseBtn = document.getElementById(`collapse-btn-${sectionId}`);
  const collapseText = collapseBtn.querySelector(".collapse-text");
  const collapseIcon = collapseBtn.querySelector("svg");

  if (tableContainer.style.display === "none") {
    tableContainer.style.display = "block";
    collapseText.textContent = "Collapse Table";
    collapseIcon.style.transform = "rotate(0deg)";
  } else {
    tableContainer.style.display = "none";
    collapseText.textContent = "Expand Table";
    collapseIcon.style.transform = "rotate(180deg)";
  }
}

function showSection(sectionName) {
  const sections = document.querySelectorAll(
    ".appointment-section, .bg-red-50\\/60",
  );
  sections.forEach((section) => {
    section.style.display = "none";
  });

  const buttons = document.querySelectorAll('[id$="-btn"]');
  buttons.forEach((btn) => {
    btn.classList.remove("bg-nhd-blue/80", "text-white", "bg-red-500/80");
    btn.classList.add("bg-gray-200/80", "text-gray-700");
  });

  if (sectionName === "all") {
    sections.forEach((section) => {
      section.style.display = "block";
    });
    document
      .getElementById("all-btn")
      .classList.remove("bg-gray-200/80", "text-gray-700");
    document
      .getElementById("all-btn")
      .classList.add("bg-nhd-blue/80", "text-white");
  } else {
    const targetSection =
      document.getElementById(sectionName + "-section") ||
      document.querySelector(".bg-red-50\\/60");
    if (targetSection) {
      targetSection.style.display = "block";
    }

    const targetBtn = document.getElementById(sectionName + "-btn");
    if (targetBtn) {
      targetBtn.classList.remove("bg-gray-200/80", "text-gray-700");
      if (sectionName === "pending-cancellation-requests") {
        targetBtn.classList.add("bg-red-500/80", "text-white");
      } else {
        targetBtn.classList.add("bg-nhd-blue/80", "text-white");
      }
    }
  }
}
window.addEventListener("load", function () {
  const urlParams = new URLSearchParams(window.location.search);
  const filter = urlParams.get("filter");
  if (filter) {
    showSection(filter);
  } else {
    // If no filter is specified in the URL, default to showing 'all'
    showSection("all");
  }
});

class PaginationManager {
  constructor() {
    this.paginationStates = {};
    this.init();
  }

  init() {
    const sections = document.querySelectorAll("[data-section]");
    sections.forEach((section) => {
      const sectionId = section.getAttribute("data-section");
      this.initializeSection(sectionId);
    });

    const mobileSections = document.querySelectorAll('[id^="mobile-view-"]');
    mobileSections.forEach((section) => {
      const sectionId = section.id.replace("mobile-view-", "");
      this.initializeSection(sectionId);
    });
  }

  initializeSection(sectionId) {
    this.paginationStates[sectionId] = {
      currentPage: 1,
      rowsPerPage: 10,
      totalRows: 0,
      totalPages: 1,
    };

    this.updateRowCount(sectionId);
    this.updatePagination(sectionId);
  }

  updateRowCount(sectionId) {
    const tableBody = document.getElementById(`table-body-${sectionId}`);
    const mobileView = document.getElementById(`mobile-view-${sectionId}`);

    let totalRows = 0;

    if (tableBody) {
      totalRows = tableBody.querySelectorAll(".table-row").length;
    } else if (mobileView) {
      totalRows = mobileView.querySelectorAll(".table-row").length;
    } else {
      const pendingTable = document.querySelector(
        `[data-section="${sectionId}"]`,
      );
      if (pendingTable) {
        totalRows = pendingTable.querySelectorAll(".table-row").length;
      }
    }

    this.paginationStates[sectionId].totalRows = totalRows;
    this.paginationStates[sectionId].totalPages = Math.ceil(
      totalRows / this.paginationStates[sectionId].rowsPerPage,
    );
  }

  updatePagination(sectionId) {
    const state = this.paginationStates[sectionId];
    if (!state) return;

    const startRow = (state.currentPage - 1) * state.rowsPerPage + 1;
    const endRow = Math.min(
      state.currentPage * state.rowsPerPage,
      state.totalRows,
    );

    const paginationInfo = document.getElementById(
      `pagination-info-${sectionId}`,
    );
    if (paginationInfo) {
      paginationInfo.textContent = `Showing ${startRow}-${endRow} of ${state.totalRows} entries`;
    }

    const currentPageSpan = document.getElementById(`currentPage-${sectionId}`);
    const totalPagesSpan = document.getElementById(`totalPages-${sectionId}`);
    if (currentPageSpan) currentPageSpan.textContent = state.currentPage;
    if (totalPagesSpan) totalPagesSpan.textContent = state.totalPages;

    const prevBtn = document.getElementById(`prevBtn-${sectionId}`);
    const nextBtn = document.getElementById(`nextBtn-${sectionId}`);

    if (prevBtn) {
      prevBtn.disabled = state.currentPage <= 1;
    }
    if (nextBtn) {
      nextBtn.disabled = state.currentPage >= state.totalPages;
    }

    this.showCurrentPageRows(sectionId);
  }

  showCurrentPageRows(sectionId) {
    const state = this.paginationStates[sectionId];
    if (!state) return;

    const startIndex = (state.currentPage - 1) * state.rowsPerPage;
    const endIndex = startIndex + state.rowsPerPage;

    const tableRows = document.querySelectorAll(
      `#table-body-${sectionId} .table-row`,
    );
    tableRows.forEach((row, index) => {
      if (index >= startIndex && index < endIndex) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });

    const mobileRows = document.querySelectorAll(
      `#mobile-view-${sectionId} .table-row`,
    );
    mobileRows.forEach((row, index) => {
      if (index >= startIndex && index < endIndex) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });

    if (sectionId === "pending-cancellation-requests") {
      const pendingDesktopRows = document.querySelectorAll(
        `[data-section="${sectionId}"] tbody .table-row`,
      );
      pendingDesktopRows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });

      const pendingMobileRows = document.querySelectorAll(
        ".bg-red-50\\/60 .block.lg\\:hidden .table-row",
      );
      pendingMobileRows.forEach((row, index) => {
        if (index >= startIndex && index < endIndex) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    }
  }

  changePage(sectionId, direction) {
    const state = this.paginationStates[sectionId];
    if (!state) return;

    if (direction === "prev" && state.currentPage > 1) {
      state.currentPage--;
    } else if (direction === "next" && state.currentPage < state.totalPages) {
      state.currentPage++;
    }

    this.updatePagination(sectionId);
  }

  changeRowsPerPage(sectionId, newRowsPerPage) {
    const state = this.paginationStates[sectionId];
    if (!state) return;

    state.rowsPerPage = parseInt(newRowsPerPage);
    state.currentPage = 1;
    state.totalPages = Math.ceil(state.totalRows / state.rowsPerPage);

    this.updatePagination(sectionId);
  }

  refreshPagination(sectionId) {
    this.updateRowCount(sectionId);
    this.updatePagination(sectionId);
  }
}

class SortableTableManager {
  constructor() {
    this.sortStates = {};
    this.paginationManager = new PaginationManager();
    this.initialized = false;
    this.boundHandleSort = this.handleSort.bind(this);
    // Auto-initialize but allow manual re-initialization
    this.init();
  }

  init() {
    // Always allow re-initialization to handle dynamic content

    // Remove any existing listeners first to prevent duplicates
    document.querySelectorAll(".sortable-header").forEach((header) => {
      header.removeEventListener("click", this.boundHandleSort);
    });

    const sortableHeaders = document.querySelectorAll(".sortable-header");

    sortableHeaders.forEach((header) => {
      header.addEventListener("click", this.boundHandleSort);
    });

    this.initialized = true;
  }

  handleSort(event) {
    const header = event.currentTarget;
    const table = header.closest("table");
    const section = table.getAttribute("data-section");
    const sortKey = header.getAttribute("data-sort");

    if (!this.sortStates[section]) {
      this.sortStates[section] = { key: null, direction: "asc" };
    }

    if (this.sortStates[section].key === sortKey) {
      this.sortStates[section].direction =
        this.sortStates[section].direction === "asc" ? "desc" : "asc";
    } else {
      this.sortStates[section].key = sortKey;
      this.sortStates[section].direction = "asc";
    }

    this.sortTableBySection(
      section,
      sortKey,
      this.sortStates[section].direction,
    );
    this.updateSortIndicators(
      table,
      sortKey,
      this.sortStates[section].direction,
    );

    this.paginationManager.refreshPagination(section);
  }

  sortTableBySection(section, sortKey, direction) {
    const tableBody = document.getElementById(`table-body-${section}`);
    const mobileView = document.getElementById(`mobile-view-${section}`);

    if (!tableBody && !mobileView) return;

    if (tableBody) {
      this.sortRows(tableBody, sortKey, direction);
    }

    if (mobileView) {
      this.sortRows(mobileView, sortKey, direction, true);
    }

    if (section === "pending-cancellation-requests") {
      const pendingTableBody = document.querySelector(
        `[data-section="${section}"] tbody`,
      );
      if (pendingTableBody) {
        this.sortRows(pendingTableBody, sortKey, direction);
      }
    }
  }

  sortRows(container, sortKey, direction, isMobileView = false) {
    const originalOpacity = container.style.opacity;
    container.style.opacity = "0.5";

    try {
      const rows = Array.from(container.querySelectorAll(".table-row"));

      rows.sort((a, b) => {
        let aValue, bValue;

        if (isMobileView) {
          // Mobile view sorting logic remains the same
          switch (sortKey) {
            case "DateTime":
              const aDateText =
                a.querySelector(".bg-nhd-blue\\/10, .bg-red-100")
                  ?.textContent || "";
              const bDateText =
                b.querySelector(".bg-nhd-blue\\/10, .bg-red-100")
                  ?.textContent || "";
              aValue = new Date(
                aDateText.replace("•", "").replace("#", "").trim(),
              );
              bValue = new Date(
                bDateText.replace("•", "").replace("#", "").trim(),
              );
              break;
            case "DoctorFirstName":
            case "PatientFirstName":
              aValue = a.querySelector("h4")?.textContent.toLowerCase() || "";
              bValue = b.querySelector("h4")?.textContent.toLowerCase() || "";
              break;
            case "PatientEmail":
              const aEmailEl = a.querySelectorAll(".text-sm.text-gray-600")[0];
              const bEmailEl = b.querySelectorAll(".text-sm.text-gray-600")[0];
              aValue = aEmailEl?.textContent.toLowerCase() || "";
              bValue = bEmailEl?.textContent.toLowerCase() || "";
              break;
            case "AppointmentType":
              aValue =
                a
                  .querySelector(".bg-gray-100\\/60")
                  ?.textContent.toLowerCase() || "";
              bValue =
                b
                  .querySelector(".bg-gray-100\\/60")
                  ?.textContent.toLowerCase() || "";
              break;
            case "Status":
              aValue =
                a
                  .querySelector(".px-2.py-1.rounded-full")
                  ?.textContent.toLowerCase() || "";
              bValue =
                b
                  .querySelector(".px-2.py-1.rounded-full")
                  ?.textContent.toLowerCase() || "";
              break;
            case "PaymentID":
            case "AppointmentID":
              const aIdText =
                a.querySelector(".bg-nhd-blue\\/10")?.textContent || "";
              const bIdText =
                b.querySelector(".bg-nhd-blue\\/10")?.textContent || "";
              if (sortKey === "PaymentID") {
                const aMatch = aIdText.match(/Payment #(\d+)/);
                const bMatch = bIdText.match(/Payment #(\d+)/);
                aValue = aMatch ? parseInt(aMatch[1]) : 0;
                bValue = bMatch ? parseInt(bMatch[1]) : 0;
              } else {
                const aMatch = aIdText.match(/Appt #(\d+)/);
                const bMatch = bIdText.match(/Appt #(\d+)/);
                aValue = aMatch ? parseInt(aMatch[1]) : 0;
                bValue = bMatch ? parseInt(bMatch[1]) : 0;
              }
              break;
            case "DoctorName":
              aValue = a.querySelector("h4")?.textContent.toLowerCase() || "";
              bValue = b.querySelector("h4")?.textContent.toLowerCase() || "";
              break;
            case "DeadlineDate":
              const aDueDateEl = a.querySelector(".text-sm.text-gray-600");
              const bDueDateEl = b.querySelector(".text-sm.text-gray-600");
              if (aDueDateEl && bDueDateEl) {
                const aDueText = aDueDateEl.textContent;
                const bDueText = bDueDateEl.textContent;
                if (aDueText.includes("Due:") && bDueText.includes("Due:")) {
                  aValue = new Date(aDueText.replace("Due:", "").trim());
                  bValue = new Date(bDueText.replace("Due:", "").trim());
                } else {
                  aValue = aDueText.includes("Due:")
                    ? new Date(aDueText.replace("Due:", "").trim())
                    : new Date("9999-12-31");
                  bValue = bDueText.includes("Due:")
                    ? new Date(bDueText.replace("Due:", "").trim())
                    : new Date("9999-12-31");
                }
              } else {
                aValue = new Date("9999-12-31");
                bValue = new Date("9999-12-31");
              }
              break;
            case "PaymentMethod":
              aValue =
                a
                  .querySelector(".bg-gray-100\\/60")
                  ?.textContent.toLowerCase() || "";
              bValue =
                b
                  .querySelector(".bg-gray-100\\/60")
                  ?.textContent.toLowerCase() || "";
              break;
            case "Amount":
              const aMobileAmountEl = a.querySelector(
                ".text-lg.font-semibold.text-nhd-brown",
              );
              const bMobileAmountEl = b.querySelector(
                ".text-lg.font-semibold.text-nhd-brown",
              );
              if (aMobileAmountEl && bMobileAmountEl) {
                aValue =
                  parseFloat(
                    aMobileAmountEl.textContent
                      .replace("₱", "")
                      .replace(",", ""),
                  ) || 0;
                bValue =
                  parseFloat(
                    bMobileAmountEl.textContent
                      .replace("₱", "")
                      .replace(",", ""),
                  ) || 0;
              } else {
                aValue = aMobileAmountEl
                  ? parseFloat(
                      aMobileAmountEl.textContent
                        .replace("₱", "")
                        .replace(",", ""),
                    ) || 0
                  : 0;
                bValue = bMobileAmountEl
                  ? parseFloat(
                      bMobileAmountEl.textContent
                        .replace("₱", "")
                        .replace(",", ""),
                    ) || 0
                  : 0;
              }
              break;
            default:
              return 0;
          }
        } else {
          // Desktop view - use dynamic column detection
          switch (sortKey) {
            case "DateTime":
              // Look for date/time in different possible formats
              let aDateValue = this.extractDateTimeValue(a);
              let bDateValue = this.extractDateTimeValue(b);
              aValue = aDateValue;
              bValue = bDateValue;
              break;

            case "DoctorFirstName":
            case "DoctorName":
              // Look for doctor names - try different selectors
              aValue = this.extractDoctorName(a);
              bValue = this.extractDoctorName(b);
              break;

            case "PatientFirstName":
            case "PatientName":
              // Look for patient names
              aValue = this.extractPatientName(a);
              bValue = this.extractPatientName(b);
              break;

            case "PatientEmail":
              // Look for email addresses
              aValue = this.extractPatientEmail(a);
              bValue = this.extractPatientEmail(b);
              break;

            case "AppointmentType":
              // Look for appointment types
              aValue = this.extractAppointmentType(a);
              bValue = this.extractAppointmentType(b);
              break;

            case "Status":
              // Look for status badges
              aValue = this.extractStatus(a);
              bValue = this.extractStatus(b);
              break;

            case "Reason":
              // Look for reason text
              aValue = this.extractReason(a);
              bValue = this.extractReason(b);
              break;

            case "TotalAmount":
            case "Amount":
              // Look for amounts/revenue
              aValue = this.extractAmount(a);
              bValue = this.extractAmount(b);
              break;

            case "PaymentStatus":
              aValue =
                a.querySelector(".status-badge")?.textContent.toLowerCase() ||
                "";
              bValue =
                b.querySelector(".status-badge")?.textContent.toLowerCase() ||
                "";
              break;

            case "PaymentID":
            case "AppointmentID":
              // Extract ID numbers
              aValue = this.extractId(a, sortKey);
              bValue = this.extractId(b, sortKey);
              break;

            case "DeadlineDate":
              aValue = this.extractDeadlineDate(a);
              bValue = this.extractDeadlineDate(b);
              break;

            case "PaymentMethod":
              // Look for payment method spans
              aValue = this.extractPaymentMethod(a);
              bValue = this.extractPaymentMethod(b);
              break;

            default:
              return 0;
          }
        }

        if (aValue < bValue) return direction === "asc" ? -1 : 1;
        if (aValue > bValue) return direction === "asc" ? 1 : -1;
        return 0;
      });

      rows.forEach((row, index) => {
        row.setAttribute("data-row-index", index);
      });

      container.innerHTML = "";
      rows.forEach((row) => container.appendChild(row));
    } catch (error) {
      console.error("Failed to sort appointments:", error);
    } finally {
      container.style.opacity = originalOpacity || "1";
    }
  }

  // Helper methods to extract values from different table structures
  extractDateTimeValue(row) {
    // Try different selectors for date/time
    let dateElement =
      row.querySelector(".bg-nhd-blue\\/10 .font-medium") ||
      row.querySelector(".bg-red-100 .font-medium") ||
      row.querySelector("td:nth-child(2) .font-medium") ||
      row.querySelector("td:nth-child(1) .font-medium");
    let timeElement =
      row.querySelector(".bg-nhd-blue\\/10 .font-bold") ||
      row.querySelector(".bg-red-100 .font-bold") ||
      row.querySelector("td:nth-child(2) .font-bold") ||
      row.querySelector("td:nth-child(1) .font-bold");

    if (dateElement && timeElement) {
      const dateText = dateElement.textContent + " " + timeElement.textContent;
      return new Date(dateText);
    }

    // Try alternative selectors for different table formats
    let dateTimeElement =
      row.querySelector("td:nth-child(2)") ||
      row.querySelector("td:nth-child(1)");
    if (dateTimeElement) {
      const text = dateTimeElement.textContent.trim();
      return new Date(text);
    }

    return new Date("1900-01-01");
  }

  extractDoctorName(row) {
    // Try different selectors for doctor names
    let doctorElement =
      row.querySelector("td:nth-child(3) .font-medium") || // Patient view
      row.querySelector("td:nth-child(4) .font-medium") || // Staff view
      row.querySelector("td:nth-child(4)"); // Revenue view

    // If no direct match, search for elements containing "Dr."
    if (!doctorElement) {
      const cells = row.querySelectorAll("td .font-medium");
      for (let cell of cells) {
        if (cell.textContent.includes("Dr.")) {
          doctorElement = cell;
          break;
        }
      }
    }

    return doctorElement?.textContent.toLowerCase().trim() || "";
  }

  extractPatientName(row) {
    // Try different selectors for patient names
    let patientElement =
      row.querySelector("td:nth-child(2) .font-medium") || // Staff view patient column
      row.querySelector("td:nth-child(3) .font-medium") || // Alternative position
      row.querySelector("td:nth-child(3)"); // Revenue view

    return patientElement?.textContent.toLowerCase().trim() || "";
  }

  extractPatientEmail(row) {
    // Look for email in different positions
    let emailElement =
      row.querySelector("td:nth-child(3) .text-sm") || // Staff view
      row.querySelector("td:nth-child(2) .text-xs"); // Alternative

    // If no direct match, search for elements containing "@"
    if (!emailElement) {
      const cells = row.querySelectorAll("td .text-sm");
      for (let cell of cells) {
        if (cell.textContent.includes("@")) {
          emailElement = cell;
          break;
        }
      }
    }

    return emailElement?.textContent.toLowerCase().trim() || "";
  }

  extractAppointmentType(row) {
    // Look for appointment type spans
    let typeElement =
      row.querySelector("span.bg-gray-100\\/60") || // Common format
      row.querySelector("td:nth-child(4) span") || // Patient view
      row.querySelector("td:nth-child(5) span") || // Staff view
      row.querySelector("td:nth-child(5)") || // Revenue view
      row.querySelector(".font-medium") ||
      row.querySelector("span.rounded-full");

    return typeElement?.textContent.toLowerCase().trim() || "";
  }

  extractStatus(row) {
    // Look for status badges
    let statusElement =
      row.querySelector("span.px-2.py-1.rounded-full") || // Common status format
      row.querySelector(".status-badge") || // Payment status
      row.querySelector("td:nth-child(6) span") || // Staff view status
      row.querySelector("td:nth-child(5) span"); // Patient view status

    return statusElement?.textContent.toLowerCase().trim() || "";
  }

  extractReason(row) {
    // Look for reason text
    let reasonElement =
      row.querySelector("td:nth-child(5) .text-sm") || // Staff view
      row.querySelector("td:last-child .text-sm"); // Alternative

    return reasonElement?.textContent.toLowerCase().trim() || "";
  }

  extractAmount(row) {
    // Look for amounts in different formats
    let amountElement =
      row.querySelector(".text-green-600") || // Revenue view
      row.querySelector("td:nth-child(6)") || // Revenue column
      row.querySelector("td:nth-child(7) .font-semibold"); // Payment amount

    // If no direct match, search for elements containing "₱"
    if (!amountElement) {
      const cells = row.querySelectorAll("td, .font-bold");
      for (let cell of cells) {
        if (cell.textContent.includes("₱")) {
          amountElement = cell;
          break;
        }
      }
    }

    if (amountElement) {
      const amountText = amountElement.textContent || "₱0";
      return parseFloat(amountText.replace("₱", "").replace(",", "")) || 0;
    }
    return 0;
  }

  extractId(row, sortKey) {
    // Extract ID numbers from different formats
    if (sortKey === "PaymentID") {
      let paymentElement =
        row.querySelector("td:nth-child(1)") || row.querySelector(".font-mono");
      if (paymentElement) {
        const text = paymentElement.textContent || "";
        const match = text.match(/Payment #(\d+)/) || text.match(/#(\d+)/);
        return match ? parseInt(match[1]) : 0;
      }
    } else {
      let appointmentElement =
        row.querySelector(".font-mono") ||
        row.querySelector("td:nth-child(1)") ||
        row.querySelector("td:nth-child(2)");
      if (appointmentElement) {
        const text = appointmentElement.textContent || "";
        const match = text.match(/#(\d+)/);
        return match ? parseInt(match[1]) : 0;
      }
    }
    return 0;
  }

  extractPaymentMethod(row) {
    // Look for payment method spans
    let methodElement = row.querySelector("td:nth-child(6) span");

    // If no direct match, search for elements containing payment methods
    if (!methodElement) {
      const cells = row.querySelectorAll("td span");
      for (let cell of cells) {
        const text = cell.textContent.toLowerCase();
        if (
          text.includes("cash") ||
          text.includes("card") ||
          text.includes("online") ||
          text.includes("bank") ||
          text.includes("credit") ||
          text.includes("debit")
        ) {
          methodElement = cell;
          break;
        }
      }
    }

    return methodElement?.textContent.toLowerCase().trim() || "";
  }

  extractDeadlineDate(row) {
    // Look for deadline dates
    let deadlineElement = row.querySelector(".text-sm.text-gray-900");

    // If no direct match, search for elements containing "Due:"
    if (!deadlineElement) {
      const cells = row.querySelectorAll(".text-sm");
      for (let cell of cells) {
        if (cell.textContent.includes("Due:")) {
          deadlineElement = cell;
          break;
        }
      }
    }

    if (deadlineElement) {
      const text = deadlineElement.textContent.replace("Due:", "").trim();
      return new Date(text);
    }

    return new Date("9999-12-31");
  }

  updateSortIndicators(table, currentSortKey, direction) {
    const allHeaders = table.querySelectorAll(".sortable-header");
    allHeaders.forEach((header) => {
      header.classList.remove("sorting");
      const defaultIcon = header.querySelector(".sort-icon-default");
      const activeIcon = header.querySelector(".sort-icon-active");

      if (defaultIcon) defaultIcon.style.display = "inline-block";
      if (activeIcon) {
        activeIcon.style.display = "none";
        activeIcon.classList.remove("asc", "desc");
      }
    });

    const currentHeader = table.querySelector(
      `[data-sort="${currentSortKey}"]`,
    );
    if (currentHeader) {
      currentHeader.classList.add("sorting");
      const defaultIcon = currentHeader.querySelector(".sort-icon-default");
      const activeIcon = currentHeader.querySelector(".sort-icon-active");

      if (defaultIcon) defaultIcon.style.display = "none";
      if (activeIcon) {
        activeIcon.style.display = "inline-block";
        activeIcon.classList.add(direction);
      }
    }
  }
}

function navigatePage(sectionId, direction) {
  if (window.tableManager && window.tableManager.paginationManager) {
    window.tableManager.paginationManager.changePage(sectionId, direction);
  }
}

function handleRowsPerPageChange(sectionId, newRowsPerPage) {
  if (window.tableManager && window.tableManager.paginationManager) {
    window.tableManager.paginationManager.changeRowsPerPage(
      sectionId,
      newRowsPerPage,
    );
  }
}

document.addEventListener("DOMContentLoaded", function () {
  window.tableManager = new SortableTableManager();

  window.toggleTableCollapse = toggleTableCollapse;
  window.showSection = showSection;
  window.navigatePage = navigatePage;
  window.handleRowsPerPageChange = handleRowsPerPageChange;

  if (typeof showSection === "function" && document.getElementById("all-btn")) {
    showSection("all");
  }
});
