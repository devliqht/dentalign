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
    const allBtn = document.getElementById("all-btn");
    if (allBtn) {
      allBtn.classList.remove("bg-gray-200/80", "text-gray-700");
      allBtn.classList.add("bg-nhd-blue/80", "text-white");
    }
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
  if (filter && typeof showSection === "function") {
    showSection(filter);
  } else {
    // If no filter is specified in the URL, default to showing 'all' only if the function exists
    if (typeof showSection === "function" && document.getElementById("all-btn")) {
      showSection("all");
    }
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

                if (aDueText.includes("No deadline")) {
                  aValue = new Date("9999-12-31");
                } else if (aDueText.includes("Due:")) {
                  const dateText = aDueText.replace("Due:", "").trim();
                  aValue = new Date(dateText);
                } else {
                  const parsedDate = new Date(aDueText.trim());
                  aValue = !isNaN(parsedDate.getTime()) ? parsedDate : new Date("9999-12-31");
                }

                if (bDueText.includes("No deadline")) {
                  bValue = new Date("9999-12-31");
                } else if (bDueText.includes("Due:")) {
                  const dateText = bDueText.replace("Due:", "").trim();
                  bValue = new Date(dateText);
                } else {
                  const parsedDate = new Date(bDueText.trim());
                  bValue = !isNaN(parsedDate.getTime()) ? parsedDate : new Date("9999-12-31");
                }
              } else {
                const aDueFallback = a.querySelector(".text-gray-400");
                const bDueFallback = b.querySelector(".text-gray-400");

                aValue = aDueFallback && aDueFallback.textContent.includes("No deadline") ?
                  new Date("9999-12-31") : new Date("9999-12-31");
                bValue = bDueFallback && bDueFallback.textContent.includes("No deadline") ?
                  new Date("9999-12-31") : new Date("9999-12-31");
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
                const aText = aMobileAmountEl.textContent;
                const bText = bMobileAmountEl.textContent;

                // Handle "Not set" case
                aValue = aText.includes("Not set") ? 0 : parseFloat(aText.replace(/[₱,]/g, "")) || 0;
                bValue = bText.includes("Not set") ? 0 : parseFloat(bText.replace(/[₱,]/g, "")) || 0;
              } else {
                const aText = aMobileAmountEl?.textContent || "";
                const bText = bMobileAmountEl?.textContent || "";

                aValue = aText.includes("Not set") ? 0 : parseFloat(aText.replace(/[₱,]/g, "")) || 0;
                bValue = bText.includes("Not set") ? 0 : parseFloat(bText.replace(/[₱,]/g, "")) || 0;
              }
              break;
            default:
              return 0;
          }
        } else {
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
              // Look for status badges (appointment status)
              aValue = this.extractAppointmentStatus(a);
              bValue = this.extractAppointmentStatus(b);
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
              // Look for payment status badges
              aValue = this.extractPaymentStatus(a);
              bValue = this.extractPaymentStatus(b);
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

  // Enhanced extraction methods for PaymentManagement table
  extractDateTimeValue(row) {
    // Try PaymentManagement specific structure first (column 1)
    let dateTimeCell = row.querySelector("td:nth-child(1)");
    if (dateTimeCell) {
      let dateText = dateTimeCell.querySelector(".font-medium")?.textContent || "";
      let timeText = dateTimeCell.querySelector(".text-sm.text-gray-500")?.textContent || "";

      if (dateText && timeText) {
        return new Date(dateText + " " + timeText);
      }

      // Fallback: try to parse the entire cell content
      let cellText = dateTimeCell.textContent.trim();
      if (cellText) {
        // Extract date part (should be first line)
        let lines = cellText.split('\n').map(line => line.trim()).filter(line => line);
        if (lines.length >= 2) {
          return new Date(lines[0] + " " + lines[1]);
        }
      }
    }

    // Original extraction methods for other table types
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

  extractPatientName(row) {
    // Try PaymentManagement specific structure first (column 2)
    let patientCell = row.querySelector("td:nth-child(2)");
    if (patientCell) {
      let nameElement = patientCell.querySelector(".font-medium");
      if (nameElement) {
        return nameElement.textContent.toLowerCase().trim();
      }
    }

    // Original extraction methods for other table types
    let patientElement =
      row.querySelector("td:nth-child(2) .font-medium") || // Staff view patient column
      row.querySelector("td:nth-child(3) .font-medium") || // Alternative position
      row.querySelector("td:nth-child(3)"); // Revenue view

    return patientElement?.textContent.toLowerCase().trim() || "";
  }

  extractDoctorName(row) {
    // Try PaymentManagement specific structure first (column 3)
    let doctorCell = row.querySelector("td:nth-child(3)");
    if (doctorCell) {
      let nameElement = doctorCell.querySelector(".font-medium");
      if (nameElement) {
        return nameElement.textContent.toLowerCase().trim();
      }
    }

    // Original extraction methods for other table types
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

  extractAppointmentStatus(row) {
    // Try PaymentManagement specific structure first (column 4)
    let statusCell = row.querySelector("td:nth-child(4)");
    if (statusCell) {
      let statusElement = statusCell.querySelector(".status-badge");
      if (statusElement) {
        return statusElement.textContent.toLowerCase().trim();
      }
    }

    // Original extraction methods for other table types
    let statusElement =
      row.querySelector("span.px-2.py-1.rounded-full") || // Common status format
      row.querySelector(".status-badge") || // Payment status
      row.querySelector("td:nth-child(6) span") || // Staff view status
      row.querySelector("td:nth-child(5) span"); // Patient view status

    return statusElement?.textContent.toLowerCase().trim() || "";
  }

  extractAmount(row) {
    // Try PaymentManagement specific structure first (column 5)
    let amountCell = row.querySelector("td:nth-child(5)");
    if (amountCell) {
      let amountElement = amountCell.querySelector(".font-medium");
      if (amountElement) {
        const amountText = amountElement.textContent || "₱0";

        // Handle complex amount displays with overdue fees
        if (amountText.includes("₱")) {
          // Extract the main amount (first ₱ value)
          const amountMatch = amountText.match(/₱([\d,]+\.?\d*)/);
          if (amountMatch) {
            const numericValue = amountMatch[1].replace(/,/g, "");
            return parseFloat(numericValue) || 0;
          }
        }
      }
    }

    // Original extraction methods for other table types
    let amountElement =
      row.querySelector("td:nth-child(7) .text-sm.font-semibold.text-nhd-brown") || // Primary amount
      row.querySelector("td:nth-child(7) .text-gray-400") || // "Not set" text
      row.querySelector("td:nth-child(7)") || // Fallback to entire cell
      row.querySelector(".text-lg.font-semibold.text-nhd-brown") || // Mobile view amount
      row.querySelector(".text-green-600"); // Revenue view

    // If no direct match, search for elements containing "₱"
    if (!amountElement) {
      const cells = row.querySelectorAll("td");
      for (let cell of cells) {
        if (cell.textContent.includes("₱")) {
          amountElement = cell;
          break;
        }
      }
    }

    if (amountElement) {
      const amountText = amountElement.textContent || "₱0";

      // Handle "Not set" case
      if (amountText.includes("Not set")) {
        return 0;
      }

      // Extract numeric value from text like "₱1,250.00"
      const amountMatch = amountText.match(/₱([\d,]+\.?\d*)/);
      if (amountMatch) {
        const numericValue = amountMatch[1].replace(/,/g, "");
        return parseFloat(numericValue) || 0;
      }

      // Fallback: remove all non-numeric characters except decimal point
      const fallbackValue = amountText.replace(/[^0-9.]/g, "");
      return parseFloat(fallbackValue) || 0;
    }

    return 0;
  }

  extractPaymentStatus(row) {
    // Try PaymentManagement specific structure first (column 6)
    let paymentStatusCell = row.querySelector("td:nth-child(6)");
    if (paymentStatusCell) {
      let statusElement = paymentStatusCell.querySelector(".status-badge");
      if (statusElement) {
        return statusElement.textContent.toLowerCase().trim();
      }
    }

    // Fallback to general status extraction
    let statusElement = row.querySelector(".status-badge");
    return statusElement?.textContent.toLowerCase().trim() || "";
  }

  extractPatientEmail(row) {
    // Look for email in various positions - typically in a text-gray-600 element
    let emailElement =
      row.querySelector("td:nth-child(4) .text-sm.text-gray-600") || // Contact column in pending cancellation
      row.querySelector("td:nth-child(3) .text-xs.text-gray-500") || // Patient column secondary text in main tables
      row.querySelector(".text-sm.text-gray-600") || // General email styling
      row.querySelector("td .text-gray-600");

    if (emailElement) {
      const text = emailElement.textContent.trim();
      // Check if it contains @ symbol to confirm it's an email
      if (text.includes("@")) {
        return text.toLowerCase();
      }
    }

    // Fallback: search all cells for email patterns
    const cells = row.querySelectorAll("td");
    for (let cell of cells) {
      const text = cell.textContent.trim();
      if (text.includes("@")) {
        return text.toLowerCase();
      }
    }

    return "";
  }

  extractAppointmentType(row) {
    // Look for appointment type - typically in a gray badge
    let typeElement =
      row.querySelector("td:nth-child(5) span.bg-gray-100\\/60") || // Type column in both pending cancellation and main tables
      row.querySelector("td:nth-child(5) .bg-gray-100\\/60") || // Alternative selector
      row.querySelector(".bg-gray-100\\/60.text-gray-700") || // General type styling
      row.querySelector("span.bg-gray-100\\/60");

    return typeElement?.textContent.toLowerCase().trim() || "";
  }

  extractReason(row) {
    // Look for reason text - typically in the last few columns
    let reasonElement =
      row.querySelector("td:nth-child(6) .text-sm.text-gray-600") || // Reason column in pending cancellation
      row.querySelector("td:nth-child(6) .text-gray-400") || // Empty reason placeholder
      row.querySelector("td .max-w-xs .text-sm") || // Truncated reason text
      row.querySelector("td .text-gray-600.truncate"); // Truncated styling

    if (reasonElement) {
      const text = reasonElement.textContent.trim();
      // Return empty string for placeholder text
      if (text === "-" || text.includes("italic")) {
        return "";
      }
      return text.toLowerCase();
    }

    return "";
  }

  extractId(row, sortKey) {
    // Look for ID numbers in various formats
    let idElement =
      row.querySelector("td:nth-child(1) .bg-nhd-blue\\/10") || // Appointment ID column
      row.querySelector(".bg-nhd-blue\\/10") || // General ID styling
      row.querySelector(".bg-red-100"); // Pending cancellation ID styling

    if (idElement) {
      const text = idElement.textContent.trim();

      if (sortKey === "PaymentID") {
        const match = text.match(/Payment #(\d+)/);
        return match ? parseInt(match[1]) : 0;
      } else if (sortKey === "AppointmentID") {
        const match = text.match(/#(\d+)/);
        return match ? parseInt(match[1]) : 0;
      }
    }

    return 0;
  }

  extractDeadlineDate(row) {
    // Look for deadline dates - this might not be used in appointment tables
    let deadlineElement =
      row.querySelector(".text-sm.text-gray-600") ||
      row.querySelector(".text-gray-400");

    if (deadlineElement) {
      const text = deadlineElement.textContent;

      if (text.includes("No deadline")) {
        return new Date("9999-12-31");
      } else if (text.includes("Due:")) {
        const dateText = text.replace("Due:", "").trim();
        return new Date(dateText);
      } else {
        const parsedDate = new Date(text.trim());
        return !isNaN(parsedDate.getTime()) ? parsedDate : new Date("9999-12-31");
      }
    }

    return new Date("9999-12-31");
  }

  extractPaymentMethod(row) {
    // Look for payment method - typically in a badge or span
    let methodElement =
      row.querySelector(".bg-gray-100\\/60") ||
      row.querySelector("span.px-2.py-1.rounded") ||
      row.querySelector(".payment-method");

    return methodElement?.textContent.toLowerCase().trim() || "";
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

  // Only call showSection if the elements exist
  if (typeof showSection === "function" && document.getElementById("all-btn")) {
    showSection("all");
  }
});
