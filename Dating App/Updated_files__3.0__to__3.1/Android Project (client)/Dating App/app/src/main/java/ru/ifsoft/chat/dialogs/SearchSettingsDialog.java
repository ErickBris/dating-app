package ru.ifsoft.chat.dialogs;

import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.DialogInterface;
import android.content.DialogInterface.OnClickListener;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.LinearLayout;
import android.widget.Spinner;

import ru.ifsoft.chat.R;
import ru.ifsoft.chat.constants.Constants;


public class SearchSettingsDialog extends DialogFragment implements Constants {

    CheckBox genderMaleCheckBox, genderFemaleCheckBox, onlineCheckBox, photoCheckBox, proModeCheckBox;
    Spinner ageTo, ageFrom;

    private int searchGender, searchOnline, searchPhoto, searchProMode, searchAgeFrom, searchAgeTo;

    /** Declaring the interface, to invoke a callback function in the implementing activity class */
    AlertPositiveListener alertPositiveListener;

    /** An interface to be implemented in the hosting activity for "OK" button click listener */
    public interface AlertPositiveListener {

        public void onCloseSettingsDialog(int searchGender, int searchOnline, int searchPhoto, int searchProMode, int searchAgeFrom, int searchAgeTo);
    }

    /** This is a callback method executed when this fragment is attached to an activity.
     *  This function ensures that, the hosting activity implements the interface AlertPositiveListener
     * */
    public void onAttach(android.app.Activity activity) {

        super.onAttach(activity);

        try {

            alertPositiveListener = (AlertPositiveListener) activity;

        } catch(ClassCastException e){

            // The hosting activity does not implemented the interface AlertPositiveListener
            throw new ClassCastException(activity.toString() + " must implement AlertPositiveListener");
        }
    }

    /** This is the OK button listener for the alert dialog,
     *  which in turn invokes the method onPositiveClick(position)
     *  of the hosting activity which is supposed to implement it
     */
    OnClickListener positiveListener = new OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {

            alertPositiveListener.onCloseSettingsDialog(searchGender, searchOnline, searchPhoto, searchProMode, searchAgeFrom, searchAgeTo);
        }
    };

    /** This is the OK button listener for the alert dialog,
     *  which in turn invokes the method onPositiveClick(position)
     *  of the hosting activity which is supposed to implement it
     */
    OnClickListener negativeListener = new OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {

            //
        }
    };

    /** This is a callback method which will be executed
     *  on creating this fragment
     */
    @Override
    public Dialog onCreateDialog(Bundle savedInstanceState) {

        /** Getting the arguments passed to this fragment */
        Bundle bundle = getArguments();

        searchGender = bundle.getInt("searchGender");
        searchOnline = bundle.getInt("searchOnline");
        searchPhoto = bundle.getInt("searchPhoto");
        searchProMode = bundle.getInt("searchProMode");
        searchAgeFrom = bundle.getInt("searchAgeFrom");
        searchAgeTo = bundle.getInt("searchAgeTo");

        /** Creating a builder for the alert dialog window */
        AlertDialog.Builder b = new AlertDialog.Builder(getActivity());

        /** Setting a title for the window */
        b.setTitle(getText(R.string.label_search_settings_dialog_title));

        LinearLayout view = (LinearLayout) getActivity().getLayoutInflater().inflate(R.layout.dialog_search_settings, null);

        b.setView(view);

        genderMaleCheckBox = (CheckBox) view.findViewById(R.id.genderMaleCheckBox);
        genderFemaleCheckBox = (CheckBox) view.findViewById(R.id.genderFemaleCheckBox);
        onlineCheckBox = (CheckBox) view.findViewById(R.id.onlineCheckBox);
        photoCheckBox = (CheckBox) view.findViewById(R.id.photoCheckBox);
        proModeCheckBox = (CheckBox) view.findViewById(R.id.proModeCheckBox);

        ageFrom = (Spinner) view.findViewById(R.id.ageFrom);
        ageTo = (Spinner) view.findViewById(R.id.ageTo);

        ArrayAdapter<String> spinnerAdapter = new ArrayAdapter<String>(getActivity(), android.R.layout.simple_spinner_item, android.R.id.text1);
        spinnerAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        ageFrom.setAdapter(spinnerAdapter);
        spinnerAdapter.add(getString(R.string.age_item_from_1));
        spinnerAdapter.add(getString(R.string.age_item_from_2));
        spinnerAdapter.add(getString(R.string.age_item_from_3));
        spinnerAdapter.add(getString(R.string.age_item_from_4));
        spinnerAdapter.add(getString(R.string.age_item_from_5));
        spinnerAdapter.add(getString(R.string.age_item_from_6));
        spinnerAdapter.add(getString(R.string.age_item_from_7));
        spinnerAdapter.notifyDataSetChanged();

        ArrayAdapter<String> spinnerAdapter2 = new ArrayAdapter<String>(getActivity(), android.R.layout.simple_spinner_item, android.R.id.text1);
        spinnerAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        ageTo.setAdapter(spinnerAdapter2);
        spinnerAdapter2.add(getString(R.string.age_item_to_1));
        spinnerAdapter2.add(getString(R.string.age_item_to_2));
        spinnerAdapter2.add(getString(R.string.age_item_to_3));
        spinnerAdapter2.add(getString(R.string.age_item_to_4));
        spinnerAdapter2.add(getString(R.string.age_item_to_5));
        spinnerAdapter2.add(getString(R.string.age_item_to_6));
        spinnerAdapter2.add(getString(R.string.age_item_to_7));
        spinnerAdapter2.notifyDataSetChanged();

        setGender(searchGender);
        setOnline(searchOnline);
        setPhoto(searchPhoto);
        setProMode(searchProMode);
        setAgeFrom(searchAgeFrom);
        setAgeTo(searchAgeTo);


        /** Setting a positive button and its listener */

        b.setPositiveButton(getText(R.string.action_ok), positiveListener);

        b.setNegativeButton(getText(R.string.action_cancel), negativeListener);


        b.setOnKeyListener(new Dialog.OnKeyListener() {

            @Override
            public boolean onKey(DialogInterface arg0, int keyCode, KeyEvent event) {
                // TODO Auto-generated method stub
                if (keyCode == KeyEvent.KEYCODE_BACK) {

                    return true;
                }

                return true;
            }
        });

        /** Creating the alert dialog window using the builder class */
        final AlertDialog d = b.create();

        d.setOnShowListener(new DialogInterface.OnShowListener() {

            @Override
            public void onShow(DialogInterface dialog) {

                final DialogInterface dlg = dialog;

                final Button b = d.getButton(AlertDialog.BUTTON_POSITIVE);
                b.setOnClickListener(new View.OnClickListener() {

                    @Override
                    public void onClick(View view) {
                        // TODO Do something

                        d.dismiss();
                        alertPositiveListener.onCloseSettingsDialog(getGender(), getOnline(), getPhoto(), getProMode(), getAgeFrom(), getAgeTo());
                    }
                });

                Button p = d.getButton(AlertDialog.BUTTON_NEGATIVE);
                p.setOnClickListener(new View.OnClickListener() {

                    @Override
                    public void onClick(View view) {
                        // TODO Do something

                        d.dismiss();
                    }
                });
            }
        });

        d.setCanceledOnTouchOutside(false);
        d.setCancelable(false);

        /** Return the alert dialog window */
        return d;
    }

    public int getGender() {

        if (genderFemaleCheckBox.isChecked() && genderMaleCheckBox.isChecked()) {

            return -1;
        }

        if (genderMaleCheckBox.isChecked()) {

            return 0;
        }

        if (genderFemaleCheckBox.isChecked()) {

            return 1;
        }

        return -1;
    }

    public void setGender(int gender) {

        switch (gender) {

            case 0: {

                genderMaleCheckBox.setChecked(true);
                genderFemaleCheckBox.setChecked(false);

                break;
            }

            case 1: {

                genderMaleCheckBox.setChecked(false);
                genderFemaleCheckBox.setChecked(true);

                break;
            }

            default: {

                genderMaleCheckBox.setChecked(true);
                genderFemaleCheckBox.setChecked(true);

                break;
            }
        }
    }

    public int getOnline() {

        if (onlineCheckBox.isChecked()) {

            return 0;
        }

        return -1;
    }

    public void setOnline(int online) {

        if (online == -1) {

            onlineCheckBox.setChecked(false);

        } else {

            onlineCheckBox.setChecked(true);
        }
    }

    public int getPhoto() {

        if (photoCheckBox.isChecked()) {

            return 0;
        }

        return -1;
    }

    public void setPhoto(int photo) {

        if (photo == -1) {

            photoCheckBox.setChecked(false);

        } else {

            photoCheckBox.setChecked(true);
        }
    }

    public int getProMode() {

        if (proModeCheckBox.isChecked()) {

            return 0;
        }

        return -1;
    }

    public void setProMode(int proMode) {

        if (proMode == -1) {

            proModeCheckBox.setChecked(false);

        } else {

            proModeCheckBox.setChecked(true);
        }
    }

    public void setAgeFrom(int age) {

        switch (age) {

            case 13: {

                ageFrom.setSelection(0);

                break;
            }

            case 18: {

                ageFrom.setSelection(1);

                break;
            }

            case 25: {

                ageFrom.setSelection(2);

                break;
            }

            case 30: {

                ageFrom.setSelection(3);

                break;
            }

            case 35: {

                ageFrom.setSelection(4);

                break;
            }

            case 40: {

                ageFrom.setSelection(5);

                break;
            }

            case 45: {

                ageFrom.setSelection(6);

                break;
            }

            default: {

                ageFrom.setSelection(0);

                break;
            }
        }
    }

    public int getAgeFrom() {

        int age = 13;

        switch (ageFrom.getSelectedItemPosition()) {

            case 0: {

                age = 13;

                break;
            }

            case 1: {

                age = 18;

                break;
            }

            case 2: {

                age = 25;

                break;
            }

            case 3: {

                age = 30;

                break;
            }

            case 4: {

                age = 35;

                break;
            }

            case 5: {

                age = 40;

                break;
            }

            case 6: {

                age = 45;

                break;
            }

            default: {

                age = 13;

                break;
            }
        }

        return age;
    }

    public void setAgeTo(int age) {

        switch (age) {

            case 20: {

                ageTo.setSelection(0);

                break;
            }

            case 27: {

                ageTo.setSelection(1);

                break;
            }

            case 38: {

                ageTo.setSelection(2);

                break;
            }

            case 43: {

                ageTo.setSelection(3);

                break;
            }

            case 50: {

                ageTo.setSelection(4);

                break;
            }

            case 70: {

                ageTo.setSelection(5);

                break;
            }

            default: {

                ageTo.setSelection(6);

                break;
            }
        }
    }

    public int getAgeTo() {

        int age = 110;

        switch (ageTo.getSelectedItemPosition()) {

            case 0: {

                age = 20;

                break;
            }

            case 1: {

                age = 27;

                break;
            }

            case 2: {

                age = 38;

                break;
            }

            case 3: {

                age = 43;

                break;
            }

            case 4: {

                age = 50;

                break;
            }

            case 5: {

                age = 70;

                break;
            }

            default: {

                age = 110;

                break;
            }
        }

        return age;
    }
}